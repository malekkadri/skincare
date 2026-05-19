<?php

namespace App\Providers;

use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        View::composer(['public.*', 'booking.*'], function ($view): void {
            $view->with('settings', Setting::current());
        });

        View::composer('public.layouts.app', function ($view): void {
            $view->with('navServiceCategories', ServiceCategory::query()
                ->active()
                ->ordered()
                ->with(['services' => fn ($query) => $query->active()->ordered()])
                ->get());
        });
    }
}
