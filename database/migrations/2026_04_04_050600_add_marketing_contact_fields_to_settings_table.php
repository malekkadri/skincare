<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->string('contact_page_title_fr')->nullable()->after('hero_button_text_en');
            $table->string('contact_page_title_en')->nullable()->after('contact_page_title_fr');
            $table->text('contact_intro_fr')->nullable()->after('contact_page_title_en');
            $table->text('contact_intro_en')->nullable()->after('contact_intro_fr');
            $table->string('map_embed_url')->nullable()->after('contact_intro_en');
            $table->text('opening_hours_fr')->nullable()->after('map_embed_url');
            $table->text('opening_hours_en')->nullable()->after('opening_hours_fr');
            $table->string('hero_secondary_button_text_fr')->nullable()->after('opening_hours_en');
            $table->string('hero_secondary_button_text_en')->nullable()->after('hero_secondary_button_text_fr');
            $table->string('hero_secondary_button_url')->nullable()->after('hero_secondary_button_text_en');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'contact_page_title_fr','contact_page_title_en','contact_intro_fr','contact_intro_en','map_embed_url',
                'opening_hours_fr','opening_hours_en','hero_secondary_button_text_fr','hero_secondary_button_text_en','hero_secondary_button_url',
            ]);
        });
    }
};
