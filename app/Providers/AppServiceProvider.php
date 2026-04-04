<?php

namespace App\Providers;

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
    }
}
