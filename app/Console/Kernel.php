<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('skinbynoor:queue-reminders')->everyFiveMinutes()->timezone('Africa/Tunis');
        $schedule->command('skinbynoor:queue-followups')->everyTenMinutes()->timezone('Africa/Tunis');
        $schedule->command('skinbynoor:queue-consultation-acks')->everyTenMinutes()->timezone('Africa/Tunis');
        $schedule->command('skinbynoor:retry-whatsapp-failures')->everyTenMinutes()->timezone('Africa/Tunis');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
