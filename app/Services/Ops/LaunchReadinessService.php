<?php

namespace App\Services\Ops;

use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class LaunchReadinessService
{
    public function checks(): array
    {
        $settings = Setting::current();
        $checks = [];

        $checks[] = $this->check('app_url', 'APP_URL configured', filled(config('app.url')) && ! str_contains((string) config('app.url'), 'localhost'), 'Set APP_URL to your public domain.');
        $checks[] = $this->check('storage_link', 'Storage symlink exists', File::exists(public_path('storage')), 'Run php artisan storage:link.');
        $checks[] = $this->check('queue_connection', 'Queue configured', config('queue.default') !== 'sync', 'Use a persistent queue driver in staging/production.');
        $checks[] = $this->check('super_admin', 'Active super admin exists', User::query()->where('is_active', true)->where('role', 'super_admin')->exists(), 'Create at least one active super admin user.');
        $checks[] = $this->check('logo', 'Logo configured', filled($settings->logo_path), 'Upload logo in Website Settings.');
        $checks[] = $this->check('active_services', 'At least one active service', Service::query()->active()->exists(), 'Publish at least one active service.');
        $checks[] = $this->check('booking_state', 'Booking mode visible', is_bool($settings->booking_enabled), 'Set booking enabled/disabled in availability settings.');

        $whatsAppConfigured = ! $settings->whatsapp_enabled || filled($settings->whatsapp_business_number);
        $checks[] = $this->check('whatsapp', 'WhatsApp configured or intentionally disabled', $whatsAppConfigured, 'Disable WhatsApp or provide business number/provider credentials.');

        $aiConfigured = ! $settings->ai_enabled || filled($settings->ai_api_key);
        $checks[] = $this->check('ai', 'AI configured or intentionally disabled', $aiConfigured, 'Disable AI or configure provider API key.');

        $sitemapOk = false;
        try {
            $sitemapOk = filled(route('sitemap'));
        } catch (\Throwable) {
            $sitemapOk = false;
        }
        $checks[] = $this->check('sitemap', 'Sitemap route available', $sitemapOk, 'Confirm sitemap route registration and APP_URL.');

        $healthOk = false;
        try {
            $healthOk = filled(route('health.probe'));
        } catch (\Throwable) {
            $healthOk = false;
        }
        $checks[] = $this->check('health', 'Health endpoint available', $healthOk, 'Confirm health route registration.');

        return $checks;
    }

    public function summary(array $checks): array
    {
        $passed = collect($checks)->where('status', 'ok')->count();
        $total = count($checks);

        return [
            'passed' => $passed,
            'total' => $total,
            'failed' => $total - $passed,
            'cache_ready' => $this->configCacheReady(),
        ];
    }

    public function configCacheReady(): bool
    {
        try {
            Artisan::call('route:list');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function check(string $key, string $label, bool $ok, string $hint): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'status' => $ok ? 'ok' : 'warn',
            'message' => $ok ? 'Ready' : $hint,
        ];
    }
}
