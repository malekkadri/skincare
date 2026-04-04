<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline_fr',
        'site_tagline_en',
        'logo_path',
        'favicon_path',
        'phone',
        'whatsapp_number',
        'address_fr',
        'address_en',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'default_language',
        'supported_languages',
        'default_currency',
        'supported_currencies',
        'timezone',
        'hero_title_fr',
        'hero_title_en',
        'hero_subtitle_fr',
        'hero_subtitle_en',
        'hero_button_text_fr',
        'hero_button_text_en',
    ];

    protected $casts = [
        'supported_languages' => 'array',
        'supported_currencies' => 'array',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::disk('public')->url($this->logo_path) : null;
    }

    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon_path ? Storage::disk('public')->url($this->favicon_path) : null;
    }

    public function localized(string $baseField, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $field = "{$baseField}_{$locale}";

        return $this->{$field} ?? null;
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'site_name' => 'Skin by Noor',
            'default_language' => 'fr',
            'supported_languages' => ['fr', 'en'],
            'default_currency' => 'TND',
            'supported_currencies' => ['TND', 'EUR'],
            'timezone' => 'Africa/Tunis',
        ]);
    }
}
