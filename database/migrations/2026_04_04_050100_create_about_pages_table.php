<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title_fr');
            $table->string('title_en');
            $table->text('intro_fr')->nullable();
            $table->text('intro_en')->nullable();
            $table->longText('story_fr')->nullable();
            $table->longText('story_en')->nullable();
            $table->longText('philosophy_fr')->nullable();
            $table->longText('philosophy_en')->nullable();
            $table->text('qualifications_fr')->nullable();
            $table->text('qualifications_en')->nullable();
            $table->string('image_path')->nullable();
            $table->string('meta_title_fr')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->text('meta_description_fr')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
