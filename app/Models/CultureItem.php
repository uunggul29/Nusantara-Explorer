<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CultureItem extends Model
{
    protected $fillable = [
        'province_id',
        'category',
        'title',
        'image_path',
        'sort_order',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
