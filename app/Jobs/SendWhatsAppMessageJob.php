<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\WhatsAppMessageLog;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $logId, public bool $force = false)
    {
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        $log = WhatsAppMessageLog::query()->with(['appointment.customer', 'consultation.customer', 'customer'])->find($this->logId);
        if (! $log) {
            return;
        }

        if ($log->status === 'sent' && ! $this->force) {
            return;
        }

        $settings = Setting::current();
        $maxAttempts = (int) ($settings->max_whatsapp_retry_attempts ?: 3);
        if (! $this->force && $log->attempts >= $maxAttempts) {
            return;
        }

        $log->update([
            'status' => 'processing',
            'attempts' => $log->attempts + 1,
            'failed_at' => null,
        ]);

        $result = $whatsAppService->sendLogEntry($log);

        if ($result['success']) {
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'failed_at' => null,
                'error_code' => null,
                'next_retry_at' => null,
                'message_body' => $result['message_body'] ?? $log->message_body,
                'provider_response' => $result['provider_response'] ?? null,
            ]);

            return;
        }

        $attempts = $log->fresh()->attempts;
        $exceeded = $attempts >= $maxAttempts;
        $backoff = (int) ($settings->whatsapp_retry_backoff_minutes ?: 10);

        $log->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_code' => $result['error_code'] ?? 'send_failed',
            'provider_response' => $result['provider_response'] ?? $result['error'] ?? null,
            'next_retry_at' => $exceeded ? null : now()->addMinutes($backoff),
        ]);
    }
}
