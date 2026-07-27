<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('display_name_html')->nullable();
            $table->string('capital_city');
            $table->string('region_group')->nullable();
            $table->string('hero_image_path');
            $table->decimal('map_lat', 10, 7);
            $table->decimal('map_lng', 10, 7);
            $table->string('mini_game_embed_id');
            $table->longText('about_intro_html');
            $table->json('about_intro_images')->nullable();
            $table->longText('history_article_html');
            $table->json('history_top_images');
            $table->string('history_bottom_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
