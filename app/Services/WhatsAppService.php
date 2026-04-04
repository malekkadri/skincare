<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Setting;
use App\Models\WhatsAppMessageLog;
use App\Models\WhatsAppTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function __construct(protected WhatsAppTemplateRenderer $renderer)
    {
    }

    public function sendBookingConfirmation(Appointment $appointment): bool
    {
        return $this->sendForAppointment('booking_confirmation', $appointment, Setting::current()->send_booking_confirmation_whatsapp);
    }

    public function sendBookingCancellation(Appointment $appointment): bool
    {
        return $this->sendForAppointment('booking_cancellation', $appointment, Setting::current()->send_booking_cancellation_whatsapp);
    }

    public function sendBookingRescheduled(Appointment $appointment): bool
    {
        return $this->sendForAppointment('booking_rescheduled', $appointment, Setting::current()->send_booking_reschedule_whatsapp);
    }

    protected function sendForAppointment(string $templateKey, Appointment $appointment, bool $eventEnabled): bool
    {
        $settings = Setting::current();
        $language = $appointment->preferred_language ?: ($appointment->customer?->preferred_language ?? 'fr');

        if (! $settings->whatsapp_enabled || ! $eventEnabled) {
            $this->createLog($templateKey, $language, $appointment, 'skipped', null, 'WhatsApp disabled for this event.');

            return false;
        }

        $template = WhatsAppTemplate::query()
            ->active()
            ->where('key', $templateKey)
            ->where('language', $language)
            ->first();

        if (! $template) {
            $this->createLog($templateKey, $language, $appointment, 'failed', null, 'Missing active template.');

            return false;
        }

        $message = $this->renderer->render($template->message_body, $this->variables($appointment, $settings));
        $recipient = $appointment->customer?->phone;

        $this->createLog($templateKey, $language, $appointment, 'sent', $message, json_encode([
            'provider' => $settings->whatsapp_provider ?: 'log',
            'base_url' => $settings->whatsapp_api_base_url,
        ], JSON_UNESCAPED_UNICODE));

        Log::info('WhatsApp placeholder send', [
            'template' => $templateKey,
            'recipient' => $recipient,
            'message' => $message,
            'appointment_id' => $appointment->id,
        ]);

        return true;
    }

    protected function variables(Appointment $appointment, Setting $settings): array
    {
        $tz = $settings->timezone ?: 'Africa/Tunis';
        $date = Carbon::parse($appointment->appointment_date->toDateString(), $tz);
        $time = Carbon::parse($appointment->appointment_date->toDateString().' '.$appointment->start_time, $tz);

        return [
            'client_name' => $appointment->customer?->full_name ?? 'Client',
            'service_name' => $appointment->preferred_language === 'fr' ? $appointment->service_name_snapshot_fr : $appointment->service_name_snapshot_en,
            'appointment_date' => $date->format('d/m/Y'),
            'appointment_time' => $time->format('H:i'),
            'business_name' => $settings->site_name,
            'whatsapp_number' => $settings->whatsapp_business_number ?: $settings->whatsapp_number,
        ];
    }

    protected function createLog(string $templateKey, string $language, Appointment $appointment, string $status, ?string $body, ?string $providerResponse): void
    {
        WhatsAppMessageLog::query()->create([
            'appointment_id' => $appointment->id,
            'customer_id' => $appointment->customer_id,
            'template_key' => $templateKey,
            'language' => $language,
            'recipient_phone' => $appointment->customer?->phone,
            'message_body' => $body,
            'status' => $status,
            'provider_response' => $providerResponse,
        ]);
    }
}
