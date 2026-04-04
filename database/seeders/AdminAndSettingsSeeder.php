<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndSettingsSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@skinbynoor.test'],
            [
                'name' => 'Skin by Noor Admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Skin by Noor',
                'site_tagline_fr' => 'Beauté et soins de la peau en Tunisie',
                'site_tagline_en' => 'Skincare and beauty in Tunisia',
                'phone' => '+216 00 000 000',
                'whatsapp_number' => '+216 00 000 000',
                'address_fr' => 'Tunis, Tunisie',
                'address_en' => 'Tunis, Tunisia',
                'facebook_url' => 'https://facebook.com/skinbynoor',
                'instagram_url' => 'https://instagram.com/skinbynoor',
                'tiktok_url' => 'https://tiktok.com/@skinbynoor',
                'default_language' => 'fr',
                'supported_languages' => ['fr', 'en'],
                'default_currency' => 'TND',
                'supported_currencies' => ['TND', 'EUR'],
                'timezone' => 'Africa/Tunis',
                'hero_title_fr' => 'Révélez votre éclat naturel',
                'hero_title_en' => 'Reveal your natural glow',
                'hero_subtitle_fr' => 'Des soins experts pour une peau saine et lumineuse.',
                'hero_subtitle_en' => 'Expert care for healthy and radiant skin.',
                'hero_button_text_fr' => 'Prendre rendez-vous',
                'hero_button_text_en' => 'Book Now',
            ]
        );
    }
}
