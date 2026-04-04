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
        'hero_title_fr','hero_title_en','hero_subtitle_fr','hero_subtitle_en','hero_button_text_fr','hero_button_text_en',
        'hero_secondary_button_text_fr','hero_secondary_button_text_en','hero_secondary_button_url',
        'contact_page_title_fr','contact_page_title_en','contact_intro_fr','contact_intro_en','map_embed_url','opening_hours_fr','opening_hours_en',
    ];

    protected $casts = [
        'supported_languages' => 'array','supported_currencies' => 'array','booking_enabled' => 'boolean','whatsapp_enabled' => 'boolean',
        'send_booking_confirmation_whatsapp' => 'boolean','send_booking_cancellation_whatsapp' => 'boolean','send_booking_reschedule_whatsapp' => 'boolean',
    ];

    protected $hidden = ['whatsapp_api_key'];

    public function getLogoUrlAttribute(): ?string { return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null; }
    public function getFaviconUrlAttribute(): ?string { return $this->favicon_path ? Storage::disk('public')->url($this->favicon_path) : null; }
    public function localized(string $baseField, ?string $locale = null): ?string
    {
        $field = $baseField.'_'.($locale ?: app()->getLocale());
        return $this->{$field} ?? null;
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Skin by Noor','default_language' => 'fr','supported_languages' => ['fr', 'en'],'default_currency' => 'TND','supported_currencies' => ['TND', 'EUR'],
            'timezone' => 'Africa/Tunis','slot_interval_minutes' => 30,'minimum_notice_hours' => 2,'maximum_booking_days_ahead' => 30,'booking_enabled' => true,
            'whatsapp_enabled' => false,'whatsapp_provider' => 'log','whatsapp_default_country_code' => '+216',
            'send_booking_confirmation_whatsapp' => true,'send_booking_cancellation_whatsapp' => true,'send_booking_reschedule_whatsapp' => true,
        ]);
    }
}
