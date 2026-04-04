<?php

namespace App\Support;

use App\Models\Setting;

class SiteSettings
{
    protected ?Setting $cached = null;

    public function all(): Setting
    {
        return $this->cached ??= Setting::current();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()->{$key} ?? $default;
    }

    public function localized(string $baseField, ?string $locale = null): ?string
    {
        return $this->all()->localized($baseField, $locale);
    }
}
