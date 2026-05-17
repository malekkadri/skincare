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
            ['email' => 'admin@asthetika.test'],
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
                'site_tagline_fr' => 'Médecine esthétique et soins de la peau à Ennasr, Ariana',
                'site_tagline_en' => 'Aesthetic medicine and skincare in Ennasr, Ariana',
                'phone' => '+216 26 500 507',
                'whatsapp_number' => '+216 26 500 507',
                'address_fr' => 'Centre médical Tunisie Médicale, 4e étage, avenue Hédi Nouira, devant la Clinique Amilcar, Ennasr, Ariana, Tunisie',
                'address_en' => 'Centre médical Tunisie Médicale, 4th floor, Hédi Nouira Avenue, opposite Clinique Amilcar, Ennasr, Ariana, Tunisia',
                'facebook_url' => 'https://www.facebook.com/Dr.AzizSahLy/',
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
                'hero_subtitle_fr' => 'Des soins médico-esthétiques personnalisés pour une peau saine, nette et lumineuse.',
                'hero_subtitle_en' => 'Personalized medical-aesthetic treatments for healthy, clear, radiant skin.',
                'hero_button_text_fr' => 'Prendre rendez-vous',
                'hero_button_text_en' => 'Book an appointment',
                'hero_secondary_button_text_fr' => 'Nous contacter',
                'hero_secondary_button_text_en' => 'Contact us',
                'hero_secondary_button_url' => '/contact',
                'contact_page_title_fr' => 'Contactez Asthetika',
                'contact_page_title_en' => 'Contact Asthetika',
                'contact_intro_fr' => 'Pour toute question ou prise de rendez-vous, contactez Asthetika par téléphone, WhatsApp ou Instagram.',
                'contact_intro_en' => 'For questions or bookings, contact Asthetika by phone, WhatsApp, or Instagram.',
                'map_embed_url' => 'https://www.google.com/maps?q=36.8586692,10.1703668&z=15&output=embed',
                'opening_hours_fr' => 'Consultation et soins sur rendez-vous',
                'opening_hours_en' => 'Consultations and treatments by appointment',

                'seo_home_title_fr' => 'Asthetika | Médecine esthétique à Ennasr, Ariana',
                'seo_home_title_en' => 'Asthetika | Aesthetic medicine in Ennasr, Ariana',
                'seo_home_description_fr' => 'Asthetika par Dr Aziz Sahly propose des soins médico-esthétiques personnalisés, Hydrafacial et protocoles cutanés à Ennasr, Ariana.',
                'seo_home_description_en' => 'Asthetika by Dr Aziz Sahly offers personalized aesthetic medicine, Hydrafacial treatments, and skincare protocols in Ennasr, Ariana.',
                'seo_services_title_fr' => 'Services Asthetika',
                'seo_services_title_en' => 'Asthetika Services',
                'seo_services_description_fr' => 'Découvrez les soins Asthetika : Hydrafacial Essentiel, Hydrafacial Détoxifiant et protocoles personnalisés pour la peau.',
                'seo_services_description_en' => 'Discover Asthetika treatments: Hydrafacial Essential, Hydrafacial Detoxifying, and personalized skincare protocols.',
                'seo_contact_title_fr' => 'Contact Asthetika',
                'seo_contact_title_en' => 'Contact Asthetika',
                'seo_contact_description_fr' => 'Contactez Asthetika par Dr Aziz Sahly à Ennasr, Ariana pour vos rendez-vous et informations.',
                'seo_contact_description_en' => 'Contact Asthetika by Dr Aziz Sahly in Ennasr, Ariana for appointments and information.',
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
