<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => now()->toDateString(), 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
            ['loc' => route('services.index'), 'lastmod' => now()->toDateString(), 'priority' => '0.9'],
            ['loc' => route('gallery'), 'lastmod' => now()->toDateString(), 'priority' => '0.7'],
            ['loc' => route('faq'), 'lastmod' => now()->toDateString(), 'priority' => '0.7'],
            ['loc' => route('contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.8'],
        ])
            ->merge(Service::query()->active()->get(['slug', 'updated_at'])->map(fn ($service) => [
                'loc' => route('services.show', $service->slug),
                'lastmod' => optional($service->updated_at)->toDateString(),
                'priority' => '0.8',
            ]))
            ->merge(Policy::query()->active()->get(['slug', 'updated_at'])->map(fn ($policy) => [
                'loc' => route('policies.show', $policy),
                'lastmod' => optional($policy->updated_at)->toDateString(),
                'priority' => '0.5',
            ]));

        return response()->view('public.seo.sitemap', ['urls' => $urls], 200, ['Content-Type' => 'application/xml']);
    }
}
