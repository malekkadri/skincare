<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Services\Ops\LaunchReadinessService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SkinByNoorSmokeTestCommand extends Command
{
    protected $signature = 'skinbynoor:smoke-test';

    protected $description = 'Run lightweight launch smoke checks for Asthetika.';

    public function handle(LaunchReadinessService $launchReadinessService): int
    {
        $this->components->info('Asthetika smoke test');

        $checks = [
            'route_home' => fn () => filled(route('home')),
            'route_booking' => fn () => filled(route('booking.service')),
            'route_admin_login' => fn () => filled(route('admin.login')),
            'settings_exists' => fn () => Setting::query()->exists(),
            'storage_writable' => fn () => is_writable(storage_path()),
            'bootstrap_cache_writable' => fn () => is_writable(base_path('bootstrap/cache')),
            'public_storage_link' => fn () => File::exists(public_path('storage')),
            'service_category_exists' => fn () => ServiceCategory::query()->exists(),
            'service_exists' => fn () => Service::query()->exists(),
        ];

        $pass = 0;
        foreach ($checks as $label => $check) {
            $ok = false;
            try {
                $ok = (bool) $check();
            } catch (\Throwable) {
                $ok = false;
            }

            $this->line(sprintf('[%s] %s', $ok ? 'OK' : 'WARN', str_replace('_', ' ', $label)));
            if ($ok) {
                $pass++;
            }
        }

        $readiness = $launchReadinessService->summary($launchReadinessService->checks());
        $this->newLine();
        $this->line("Smoke checks passed: {$pass}/".count($checks));
        $this->line("Launch readiness passed: {$readiness['passed']}/{$readiness['total']}");
        $this->line('Queue connection: '.config('queue.default'));
        $this->line('Scheduler expected: yes (configure cron for php artisan schedule:run).');

        return self::SUCCESS;
    }
}
