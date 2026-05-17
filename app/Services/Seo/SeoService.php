<?php

namespace App\Services\Seo;

use App\Models\AboutPage;
use App\Models\Service;
use App\Models\Setting;

class SeoService
{
    public function forPage(string $key, string $canonical, ?string $fallbackTitle = null): SeoData
    {
        $settings = Setting::current();
        $locale = app()->getLocale();

        $title = $settings->{"seo_{$key}_title_{$locale}"}
            ?: $fallbackTitle
            ?: $settings->site_name
            ?: 'Asthetika';

        $description = $settings->{"seo_{$key}_description_{$locale}"}
            ?: $settings->localized('site_tagline')
            ?: 'Asthetika skincare clinic in Tunisia.';

        return new SeoData($title, $description, $canonical);
    }

    public function forService(Service $service): SeoData
    {
        $locale = app()->getLocale();

        return new SeoData(
            $service->{"meta_title_{$locale}"} ?: $service->localized_name,
            $service->{"meta_description_{$locale}"} ?: ($service->localized_short_description ?: strip_tags((string) $service->localized_description)),
            route('services.show', $service->slug),
            'article',
            $service->image_url,
        );
    }

    public function forAbout(?AboutPage $about): SeoData
    {
        return new SeoData(
            $about?->{"meta_title_".app()->getLocale()} ?: ($about?->localized_title ?: 'About Asthetika'),
            $about?->{"meta_description_".app()->getLocale()} ?: ($about?->localized_intro ?: 'Learn about Asthetika.'),
            route('about')
        );
    }
}
