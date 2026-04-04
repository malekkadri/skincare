<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'site_name','site_tagline_fr','site_tagline_en','logo_path','favicon_path','phone','whatsapp_number','address_fr','address_en',
        'facebook_url','instagram_url','tiktok_url','default_language','supported_languages','default_currency','supported_currencies','timezone',
        'slot_interval_minutes','minimum_notice_hours','maximum_booking_days_ahead','max_appointments_per_day','booking_enabled',
        'whatsapp_enabled','whatsapp_provider','whatsapp_business_number','whatsapp_api_key','whatsapp_api_base_url','whatsapp_default_country_code',
        'send_booking_confirmation_whatsapp','send_booking_cancellation_whatsapp','send_booking_reschedule_whatsapp',
        'whatsapp_automation_enabled','send_24h_reminder','send_2h_reminder','send_post_appointment_followup','send_consultation_acknowledgement',
        'reminder_24h_lead_minutes','reminder_2h_lead_minutes','followup_lead_minutes','max_whatsapp_retry_attempts','whatsapp_retry_backoff_minutes','automation_pause_until',
        'hero_title_fr','hero_title_en','hero_subtitle_fr','hero_subtitle_en','hero_button_text_fr','hero_button_text_en',
        'hero_secondary_button_text_fr','hero_secondary_button_text_en','hero_secondary_button_url',
        'contact_page_title_fr','contact_page_title_en','contact_intro_fr','contact_intro_en','map_embed_url','opening_hours_fr','opening_hours_en',
        'seo_home_title_fr','seo_home_title_en','seo_home_description_fr','seo_home_description_en',
        'seo_services_title_fr','seo_services_title_en','seo_services_description_fr','seo_services_description_en',
        'seo_gallery_title_fr','seo_gallery_title_en','seo_gallery_description_fr','seo_gallery_description_en',
        'seo_testimonials_title_fr','seo_testimonials_title_en','seo_testimonials_description_fr','seo_testimonials_description_en',
        'seo_faq_title_fr','seo_faq_title_en','seo_faq_description_fr','seo_faq_description_en',
        'seo_contact_title_fr','seo_contact_title_en','seo_contact_description_fr','seo_contact_description_en',
        'ai_enabled','ai_provider','ai_api_key','ai_model','ai_base_url','ai_temperature','ai_timeout_seconds',
        'ai_enable_consultation_summary','ai_enable_service_recommendation','ai_enable_admin_content_helper',
    ];

    protected $casts = [
        'supported_languages' => 'array','supported_currencies' => 'array','booking_enabled' => 'boolean','whatsapp_enabled' => 'boolean',
        'send_booking_confirmation_whatsapp' => 'boolean','send_booking_cancellation_whatsapp' => 'boolean','send_booking_reschedule_whatsapp' => 'boolean',
        'whatsapp_automation_enabled' => 'boolean','send_24h_reminder' => 'boolean','send_2h_reminder' => 'boolean','send_post_appointment_followup' => 'boolean',
        'send_consultation_acknowledgement' => 'boolean','automation_pause_until' => 'datetime',
        'ai_enabled' => 'boolean','ai_temperature' => 'decimal:2','ai_enable_consultation_summary' => 'boolean',
        'ai_enable_service_recommendation' => 'boolean','ai_enable_admin_content_helper' => 'boolean',
    ];

    protected $hidden = ['whatsapp_api_key', 'ai_api_key'];

    public function getLogoUrlAttribute(): ?string { return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null; }
    public function getFaviconUrlAttribute(): ?string { return $this->favicon_path ? Storage::disk('public')->url($this->favicon_path) : null; }
    public function localized(string $baseField, ?string $locale = null): ?string
    {
        $field = $baseField.'_'.($locale ?: app()->getLocale());
        return $this->{$field} ?? null;
    }

    public function seo(string $pageKey, string $field = 'title', ?string $locale = null): ?string
    {
        return $this->localized("seo_{$pageKey}_{$field}", $locale);
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Skin by Noor','default_language' => 'fr','supported_languages' => ['fr', 'en'],'default_currency' => 'TND','supported_currencies' => ['TND', 'EUR'],
            'timezone' => 'Africa/Tunis','slot_interval_minutes' => 30,'minimum_notice_hours' => 2,'maximum_booking_days_ahead' => 30,'booking_enabled' => true,
            'whatsapp_enabled' => false,'whatsapp_provider' => 'log','whatsapp_default_country_code' => '+216',
            'send_booking_confirmation_whatsapp' => true,'send_booking_cancellation_whatsapp' => true,'send_booking_reschedule_whatsapp' => true,
            'whatsapp_automation_enabled' => false,'send_24h_reminder' => true,'send_2h_reminder' => true,'send_post_appointment_followup' => true,
            'send_consultation_acknowledgement' => false,'reminder_24h_lead_minutes' => 1440,'reminder_2h_lead_minutes' => 120,'followup_lead_minutes' => 720,
            'max_whatsapp_retry_attempts' => 3,'whatsapp_retry_backoff_minutes' => 10,
            'ai_enabled' => false,'ai_provider' => 'grok','ai_model' => 'grok-2-latest','ai_timeout_seconds' => 25,'ai_temperature' => 0.30,
            'ai_enable_consultation_summary' => true,'ai_enable_service_recommendation' => true,'ai_enable_admin_content_helper' => true,
            'seo_home_title_fr' => 'Skin by Noor | Soins de la peau', 'seo_home_title_en' => 'Skin by Noor | Skincare in Tunisia',
            'seo_services_title_fr' => 'Services Skin by Noor', 'seo_services_title_en' => 'Skin by Noor Services',
        ]);
    }
}
