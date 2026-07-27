<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'display_name_html',
        'capital_city',
        'region_group',
        'hero_image_path',
        'map_lat',
        'map_lng',
        'mini_game_embed_id',
        'about_intro_html',
        'about_intro_images',
        'history_article_html',
        'history_top_images',
        'history_bottom_image',
    ];

    protected function casts(): array
    {
        return [
            'about_intro_images' => 'array',
            'history_top_images' => 'array',
            'map_lat' => 'decimal:7',
            'map_lng' => 'decimal:7',
        ];
    }

    public function cultureItems(): HasMany
    {
        return $this->hasMany(CultureItem::class)->orderBy('sort_order');
    }
}
