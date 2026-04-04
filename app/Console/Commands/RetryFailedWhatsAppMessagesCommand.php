<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppMessageJob;
use App\Models\Setting;
use App\Models\WhatsAppMessageLog;
use Illuminate\Console\Command;

class RetryFailedWhatsAppMessagesCommand extends Command
{
    protected $signature = 'skinbynoor:retry-whatsapp-failures';
    protected $description = 'Retry failed WhatsApp messages that are eligible for retry';

    public function handle(): int
    {
        $settings = Setting::current();
        $maxAttempts = (int) ($settings->max_whatsapp_retry_attempts ?: 3);

        $logs = WhatsAppMessageLog::query()
            ->where('status', 'failed')
            ->where('attempts', '<', $maxAttempts)
            ->where(function ($query) {
                $query->whereNull('next_retry_at')->orWhere('next_retry_at', '<=', now());
            })
            ->limit(100)
            ->get();

        foreach ($logs as $log) {
            SendWhatsAppMessageJob::dispatch($log->id);
        }

        $this->info('Queued '.$logs->count().' failed WhatsApp message(s) for retry.');

        return self::SUCCESS;
    }
}
