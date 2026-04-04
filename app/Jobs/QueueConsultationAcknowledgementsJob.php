<?php

namespace App\Jobs;

use App\Services\WhatsAppAutomationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueConsultationAcknowledgementsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(WhatsAppAutomationService $automationService): void
    {
        $automationService->queueConsultationAcknowledgements();
    }
}
