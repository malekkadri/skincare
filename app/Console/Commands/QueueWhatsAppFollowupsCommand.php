<?php

namespace App\Console\Commands;

use App\Services\WhatsAppAutomationService;
use Illuminate\Console\Command;

class QueueWhatsAppFollowupsCommand extends Command
{
    protected $signature = 'skinbynoor:queue-followups';
    protected $description = 'Queue due WhatsApp post-appointment follow-up messages';

    public function handle(WhatsAppAutomationService $automationService): int
    {
        $count = $automationService->queueDueFollowups();
        $this->info("Queued {$count} follow-up message(s).");

        return self::SUCCESS;
    }
}
