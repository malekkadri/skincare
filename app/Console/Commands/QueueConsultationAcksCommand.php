<?php

namespace App\Console\Commands;

use App\Services\WhatsAppAutomationService;
use Illuminate\Console\Command;

class QueueConsultationAcksCommand extends Command
{
    protected $signature = 'skinbynoor:queue-consultation-acks';
    protected $description = 'Queue due consultation acknowledgement WhatsApp messages';

    public function handle(WhatsAppAutomationService $automationService): int
    {
        $count = $automationService->queueConsultationAcknowledgements();
        $this->info("Queued {$count} consultation acknowledgement message(s).");

        return self::SUCCESS;
    }
}
