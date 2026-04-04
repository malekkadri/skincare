<?php

namespace App\Console\Commands;

use App\Services\WhatsAppAutomationService;
use Illuminate\Console\Command;

class QueueWhatsAppRemindersCommand extends Command
{
    protected $signature = 'skinbynoor:queue-reminders';
    protected $description = 'Queue due WhatsApp appointment reminders';

    public function handle(WhatsAppAutomationService $automationService): int
    {
        $count = $automationService->queueDueAppointmentReminders();
        $this->info("Queued {$count} reminder message(s).");

        return self::SUCCESS;
    }
}
