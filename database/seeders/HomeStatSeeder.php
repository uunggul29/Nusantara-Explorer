<?php

namespace Database\Seeders;

use App\Models\HomeStat;
use Illuminate\Database\Seeder;

class HomeStatSeeder extends Seeder
{
    /**
     * Transcribed verbatim from the legacy index.html "About" stat cards.
     * Islands originally used an <img>, the other two used Font Awesome classes —
     * icon_path stores either, and the Vue template decides how to render it.
     */
    public function run(): void
    {
        $stats = [
            ['label' => 'Islands', 'value' => '17,504', 'icon_path' => 'home/island.png'],
            ['label' => 'Provinces', 'value' => '38', 'icon_path' => 'fa fa-map-marker-alt'],
            ['label' => 'Million People', 'value' => '284', 'icon_path' => 'fa fa-users'],
        ];

        foreach ($stats as $i => $stat) {
            HomeStat::updateOrCreate(
                ['label' => $stat['label']],
                $stat + ['sort_order' => $i]
            );
        }
    }
}
