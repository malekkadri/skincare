<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 100)->index();
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->string('subtitle_fr')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image_path')->nullable();
            $table->string('mobile_image_path')->nullable();
            $table->string('alt_text_fr')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->string('cta_label_fr')->nullable();
            $table->string('cta_label_en')->nullable();
            $table->string('cta_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->decimal('overlay_opacity', 4, 2)->default(0.35);
            $table->timestamps();
        });

        Schema::create('home_banner_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->string('mobile_image_path')->nullable();
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->string('subtitle_fr')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('cta_label_fr')->nullable();
            $table->string('cta_label_en')->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('alt_text_fr')->nullable();
            $table->string('alt_text_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_banner_slides');
        Schema::dropIfExists('page_heroes');
    }
};
