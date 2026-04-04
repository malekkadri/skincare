<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('title_fr')->nullable();
            $table->string('title_en')->nullable();
            $table->text('content_fr')->nullable();
            $table->text('content_en')->nullable();
            $table->string('button_text_fr')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_url')->nullable();
            $table->string('secondary_button_text_fr')->nullable();
            $table->string('secondary_button_text_en')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
