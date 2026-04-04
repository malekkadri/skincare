<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPublicLocaleCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Setting::current();
        $locale = session('locale', $settings->default_language ?: 'fr');
        $currency = session('currency', $settings->default_currency ?: 'TND');

        if (! in_array($locale, ['fr', 'en'], true)) {
            $locale = 'fr';
        }

        if (! in_array($currency, ['TND', 'EUR'], true)) {
            $currency = 'TND';
        }

        app()->setLocale($locale);
        session(['locale' => $locale, 'currency' => $currency]);

        return $next($request);
    }
}
