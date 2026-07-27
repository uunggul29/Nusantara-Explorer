<?php

namespace App\Console\Commands;

use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

class MigrateProvinceContent extends Command
{
    protected $signature = 'migrate:province-content {--source= : Path to the legacy province/ folder}';

    protected $description = 'One-off import of the legacy static province/*.html files into the provinces & culture_items tables';

    /**
     * The legacy image folder names don't all match their province slug.
     * Maps the folder name as it appears in the old HTML to the slug-aligned
     * folder name it was copied under in storage/app/public/provinces.
     */
    private const FOLDER_ALIASES = [
        'kallimantan-utara' => 'kalimantan-utara',
        'ntb' => 'nusa-tenggara-barat',
        'ntt' => 'nusa-tenggara-timur',
    ];

    /**
     * slug => [lat, lng], transcribed verbatim from the legacy js/script.js `capitals` array.
     */
    private const COORDS = [
        'aceh' => [5.5546, 95.3174],
        'sumatera-utara' => [3.5952, 98.6722],
        'sumatera-barat' => [-0.9471, 100.4172],
        'riau' => [0.5071, 101.4478],
        'kepulauan-riau' => [0.9167, 104.4711],
        'jambi' => [-1.6102, 103.6126],
        'sumatera-selatan' => [-2.9909, 104.7565],
        'bengkulu' => [-3.8004, 102.2655],
        'lampung' => [-5.4295, 105.2580],
        'bangka-belitung' => [-2.1293, 106.1168],
        'dki-jakarta' => [-6.2088, 106.8456],
        'jawa-barat' => [-6.9175, 107.6191],
        'jawa-tengah' => [-6.9667, 110.4167],
        'diy' => [-7.7956, 110.3695],
        'jawa-timur' => [-7.2504, 112.7688],
        'banten' => [-6.1103, 106.1638],
        'bali' => [-8.6500, 115.2167],
        'nusa-tenggara-barat' => [-8.5833, 116.1167],
        'nusa-tenggara-timur' => [-10.1788, 123.5820],
        'kalimantan-barat' => [-0.0333, 109.3333],
        'kalimantan-tengah' => [-2.2096, 113.9166],
        'kalimantan-selatan' => [-3.3333, 114.5833],
        'kalimantan-timur' => [0.5022, 117.1536],
        'kalimantan-utara' => [2.8395, 117.3672],
        'sulawesi-utara' => [1.4748, 124.8421],
        'sulawesi-tengah' => [-0.8987, 119.8707],
        'sulawesi-selatan' => [-5.1331, 119.4139],
        'sulawesi-tenggara' => [-3.9722, 122.5182],
        'gorontalo' => [0.6998, 122.4467],
        'sulawesi-barat' => [-2.6742, 118.8887],
        'maluku' => [-3.7078, 128.2136],
        'maluku-utara' => [0.7333, 127.5500],
        'papua' => [-2.5333, 140.7167],
        'papua-barat' => [-0.8615, 134.0630],
        'papua-barat-daya' => [-0.8750, 131.2500],
        'papua-selatan' => [-8.4961, 140.4010],
        'papua-pegunungan' => [-4.1007, 138.9337],
        'papua-tengah' => [-3.3667, 135.5167],
    ];

    private const CATEGORIES = ['artifact', 'food', 'tradition', 'arts'];

    public function handle(): int
    {
        $source = rtrim($this->option('source') ?? 'C:\\laragon\\www\\Nusantara-Explorer\\province', '/\\');

        if (! is_dir($source)) {
            $this->error("Source directory not found: {$source}");

            return self::FAILURE;
        }

        $files = glob($source.'/*.html');
        sort($files);

        foreach ($files as $file) {
            $slug = pathinfo($file, PATHINFO_FILENAME);
            $this->migrateFile($slug, $file);
        }

        $this->newLine();
        $this->info('Done. Provinces: '.Province::count().', Culture items: '.\App\Models\CultureItem::count());

        return self::SUCCESS;
    }

    private function migrateFile(string $slug, string $file): void
    {
        libxml_use_internal_errors(true);
        $html = file_get_contents($file);
        $crawler = new Crawler($html);

        $header = $crawler->filter('header.header');
        $heroImage = null;
        if ($header->count()) {
            $style = $header->attr('style') ?? '';
            if (preg_match('/url\((.*?)\)/', $style, $m)) {
                $heroImage = $this->normalizeImagePath(trim($m[1], " '\""));
            }
        }

        $titleH1s = $header->count() ? $header->filter('h1.title') : new Crawler;
        $capitalCity = null;
        $displayName = null;
        if ($titleH1s->count() >= 1) {
            $capitalCity = trim((string) preg_replace('/^Welcome to\s+/i', '', $titleH1s->eq(0)->text()));
        }
        if ($titleH1s->count() >= 2) {
            $displayName = trim($titleH1s->eq(1)->text());
        }
        $name = $displayName ?: $capitalCity;

        $descCols = $crawler->filter('section#description .col-md-6');
        $aboutIntroHtml = $descCols->count() >= 1 ? $descCols->eq(0)->html() : '';
        $rightCol = $descCols->count() >= 2 ? $descCols->eq(1) : null;

        $historyTopImages = [];
        $historyArticleHtml = '';
        $historyBottomImage = null;
        if ($rightCol) {
            $imgPaths = [];
            $rightCol->filter('img')->each(function (Crawler $img) use (&$imgPaths) {
                if ($src = $img->attr('src')) {
                    $imgPaths[] = $this->normalizeImagePath($src);
                }
            });
            $historyTopImages = array_slice($imgPaths, 0, 2);
            if (count($imgPaths) > 2) {
                $historyBottomImage = end($imgPaths);
            }

            $article = $rightCol->filter('.col-12.mt-4');
            if ($article->count()) {
                $historyArticleHtml = $article->html();
            }
        }

        $cultureItems = [];
        foreach (self::CATEGORIES as $category) {
            $order = 0;
            $crawler->filter(".portfolio-container .{$category}")->each(function (Crawler $node) use (&$cultureItems, $category, &$order) {
                $img = $node->filter('img');
                $title = $node->filter('.title');
                if ($img->count() && $title->count()) {
                    $cultureItems[] = [
                        'category' => $category,
                        'title' => trim($title->text()),
                        'image_path' => $this->normalizeImagePath($img->attr('src')),
                        'sort_order' => $order++,
                    ];
                }
            });
        }

        $miniGameId = null;
        $iframe = $crawler->filter('#mini-game iframe');
        if ($iframe->count() && ($src = $iframe->attr('src')) && preg_match('#interacty\.me/([a-f0-9]+)/#i', $src, $m)) {
            $miniGameId = $m[1];
        }

        [$lat, $lng] = self::COORDS[$slug] ?? [0, 0];

        if (! isset(self::COORDS[$slug])) {
            $this->warn("[{$slug}] no coordinates found in COORDS map");
        }
        if (! $heroImage) {
            $this->warn("[{$slug}] missing hero image");
        }
        if (count($historyTopImages) < 2) {
            $this->warn("[{$slug}] expected >= 2 history images, got ".count($historyTopImages));
        }
        if (! $miniGameId) {
            $this->warn("[{$slug}] missing mini-game embed id");
        }
        if (empty($cultureItems)) {
            $this->warn("[{$slug}] no culture items found");
        }
        if (! $displayName) {
            $this->line("[{$slug}] no separate province-name heading (display_name_html left null, expected for bengkulu/jambi)");
        }

        DB::transaction(function () use (
            $slug, $name, $displayName, $capitalCity, $heroImage, $lat, $lng,
            $miniGameId, $aboutIntroHtml, $historyArticleHtml, $historyTopImages,
            $historyBottomImage, $cultureItems
        ) {
            $province = Province::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'display_name_html' => $displayName,
                    'capital_city' => $capitalCity,
                    'hero_image_path' => $heroImage,
                    'map_lat' => $lat,
                    'map_lng' => $lng,
                    'mini_game_embed_id' => $miniGameId,
                    'about_intro_html' => $aboutIntroHtml,
                    'history_article_html' => $historyArticleHtml,
                    'history_top_images' => $historyTopImages,
                    'history_bottom_image' => $historyBottomImage,
                ]
            );

            $province->cultureItems()->delete();
            foreach ($cultureItems as $item) {
                $province->cultureItems()->create($item);
            }
        });

        $this->info("Migrated {$slug} ({$name}) — ".count($cultureItems).' culture items');
    }

    private function normalizeImagePath(?string $src): ?string
    {
        if (! $src) {
            return null;
        }

        $src = ltrim($src, './');

        if (preg_match('#assets/imgs/([^/]+)/(.+)$#', $src, $m)) {
            $folder = self::FOLDER_ALIASES[$m[1]] ?? $m[1];

            return "provinces/{$folder}/{$m[2]}";
        }

        return $src;
    }
}
