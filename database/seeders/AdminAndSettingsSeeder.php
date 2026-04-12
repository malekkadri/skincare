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
                'is_active' => true,
                'role' => 'super_admin',
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
                'slot_interval_minutes' => 30,
                'minimum_notice_hours' => 2,
                'maximum_booking_days_ahead' => 30,
                'max_appointments_per_day' => 12,
                'booking_enabled' => true,
                'whatsapp_enabled' => false,
                'whatsapp_provider' => 'log',
                'whatsapp_business_number' => '+216 00 000 000',
                'whatsapp_default_country_code' => '+216',
                'send_booking_confirmation_whatsapp' => true,
                'send_booking_cancellation_whatsapp' => true,
                'send_booking_reschedule_whatsapp' => true,
                'whatsapp_automation_enabled' => false,
                'send_24h_reminder' => true,
                'send_2h_reminder' => true,
                'send_post_appointment_followup' => true,
                'send_consultation_acknowledgement' => false,
                'reminder_24h_lead_minutes' => 1440,
                'reminder_2h_lead_minutes' => 120,
                'followup_lead_minutes' => 720,
                'max_whatsapp_retry_attempts' => 3,
                'whatsapp_retry_backoff_minutes' => 10,
                'automation_pause_until' => null,
                'hero_title_fr' => 'Révélez votre éclat naturel',
                'hero_title_en' => 'Reveal your natural glow',
                'hero_subtitle_fr' => 'Des soins experts pour une peau saine et lumineuse.',
                'hero_subtitle_en' => 'Expert care for healthy and radiant skin.',
                'hero_button_text_fr' => 'Prendre rendez-vous',
                'hero_button_text_en' => 'Book Now',
                'hero_secondary_button_text_fr' => 'WhatsApp',
                'hero_secondary_button_text_en' => 'WhatsApp',
                'hero_secondary_button_url' => '/contact',
                'contact_page_title_fr' => 'Contactez-nous',
                'contact_page_title_en' => 'Contact Us',
                'contact_intro_fr' => 'Pour toute question, écrivez-nous sur WhatsApp.',
                'contact_intro_en' => 'For any question, message us on WhatsApp.',
                'map_embed_url' => 'https://maps.google.com',
                'opening_hours_fr' => 'Lun-Sam: 09:00-19:00',
                'opening_hours_en' => 'Mon-Sat: 09:00-19:00',

                'seo_home_title_fr' => 'Skin by Noor | Soins du visage à Tunis',
                'seo_home_title_en' => 'Skin by Noor | Skincare in Tunis',
                'seo_home_description_fr' => 'Soins experts, consultation personnalisée et réservation WhatsApp.',
                'seo_home_description_en' => 'Expert skincare, personalized consultations, and WhatsApp booking.',
                'seo_services_title_fr' => 'Services de soins - Skin by Noor',
                'seo_services_title_en' => 'Skincare Services - Skin by Noor',
                'seo_contact_title_fr' => 'Contact - Skin by Noor',
                'seo_contact_title_en' => 'Contact - Skin by Noor',
                'ai_enabled' => false,
                'ai_provider' => 'groq',
                'ai_api_key' => null,
                'ai_model' => 'llama-3.3-70b-versatile',
                'ai_base_url' => 'https://api.groq.com/openai/v1/chat/completions',
                'ai_temperature' => 0.30,
                'ai_timeout_seconds' => 25,
                'ai_enable_consultation_summary' => true,
                'ai_enable_service_recommendation' => true,
                'ai_enable_admin_content_helper' => true,
            ]
        );
    }
}
