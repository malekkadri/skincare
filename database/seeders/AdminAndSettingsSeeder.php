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
                'name' => 'Asthetika Admin',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );

        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Asthetika',
                'site_tagline_fr' => 'Médecine esthétique et soins de la peau en Tunisie',
                'site_tagline_en' => 'Aesthetic medicine and skincare in Tunisia',
                'phone' => '+216 26 500 507',
                'whatsapp_number' => '+216 26 500 507',
                'address_fr' => 'Tunis, Tunisie',
                'address_en' => 'Tunis, Tunisia',
                'facebook_url' => null,
                'instagram_url' => 'https://www.instagram.com/dr.azizsahly/',
                'tiktok_url' => null,
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
                'whatsapp_business_number' => '+216 26 500 507',
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
                'hero_title_fr' => 'Asthetika · Dr Aziz Sahly',
                'hero_title_en' => 'Asthetika · Dr Aziz Sahly',
                'hero_subtitle_fr' => 'Des protocoles médico-esthétiques personnalisés pour une peau saine et lumineuse.',
                'hero_subtitle_en' => 'Personalized medical-aesthetic protocols for healthy, radiant skin.',
                'hero_button_text_fr' => 'Prendre rendez-vous',
                'hero_button_text_en' => 'Book Now',
                'hero_secondary_button_text_fr' => 'WhatsApp',
                'hero_secondary_button_text_en' => 'WhatsApp',
                'hero_secondary_button_url' => '/contact',
                'contact_page_title_fr' => 'Contactez-nous',
                'contact_page_title_en' => 'Contact Us',
                'contact_intro_fr' => 'Pour toute question, contactez Asthetika par téléphone ou WhatsApp.',
                'contact_intro_en' => 'For any question, contact Asthetika by phone or WhatsApp.',
                'map_embed_url' => 'https://maps.app.goo.gl/5ZJnT3P7bh5WmGwU8?g_st=ipc',
                'opening_hours_fr' => 'Lun-Sam: 09:00-19:00',
                'opening_hours_en' => 'Mon-Sat: 09:00-19:00',

                'seo_home_title_fr' => 'Asthetika | Soins esthétiques à Tunis',
                'seo_home_title_en' => 'Asthetika | Aesthetic skincare in Tunis',
                'seo_home_description_fr' => 'Asthetika par Dr Aziz Sahly : soins experts, protocoles personnalisés et prise de rendez-vous rapide.',
                'seo_home_description_en' => 'Asthetika by Dr Aziz Sahly: expert treatments, personalized protocols, and fast booking.',
                'seo_services_title_fr' => 'Services Asthetika',
                'seo_services_title_en' => 'Asthetika Services',
                'seo_contact_title_fr' => 'Contact Asthetika',
                'seo_contact_title_en' => 'Contact Asthetika',
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
