<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_tagline_fr')->nullable();
            $table->string('site_tagline_en')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address_fr')->nullable();
            $table->text('address_en')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('default_language')->default('fr');
            $table->json('supported_languages');
            $table->string('default_currency')->default('TND');
            $table->json('supported_currencies');
            $table->string('timezone')->default('Africa/Tunis');
            $table->string('hero_title_fr')->nullable();
            $table->string('hero_title_en')->nullable();
            $table->text('hero_subtitle_fr')->nullable();
            $table->text('hero_subtitle_en')->nullable();
            $table->string('hero_button_text_fr')->nullable();
            $table->string('hero_button_text_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
