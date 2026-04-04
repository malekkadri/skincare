<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Consultation;
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

    public function sendLogEntry(WhatsAppMessageLog $log): array
    {
        $settings = Setting::current();
        $language = $log->language ?: 'fr';

        if (! $settings->whatsapp_enabled) {
            return ['success' => false, 'error_code' => 'whatsapp_disabled', 'error' => 'WhatsApp disabled'];
        }

        $template = WhatsAppTemplate::query()
            ->active()
            ->where('key', $log->template_key)
            ->where('language', $language)
            ->first();

        if (! $template) {
            return ['success' => false, 'error_code' => 'template_missing', 'error' => 'Missing active template'];
        }

        $recipient = $log->recipient_phone ?: $log->customer?->phone ?: $log->appointment?->customer?->phone ?: $log->consultation?->phone;
        if (! $recipient) {
            return ['success' => false, 'error_code' => 'recipient_missing', 'error' => 'Missing recipient'];
        }

        $variables = $log->appointment
            ? $this->appointmentVariables($log->appointment, $settings)
            : $this->consultationVariables($log->consultation, $settings);

        $message = $this->renderer->render($template->message_body, $variables);
        $providerResponse = json_encode([
            'provider' => $settings->whatsapp_provider ?: 'log',
            'base_url' => $settings->whatsapp_api_base_url,
        ], JSON_UNESCAPED_UNICODE);

        Log::info('WhatsApp placeholder send', [
            'template' => $log->template_key,
            'recipient' => $recipient,
            'message' => $message,
            'appointment_id' => $log->appointment_id,
            'consultation_id' => $log->related_consultation_id,
            'automation_source' => $log->automation_source,
        ]);

        return [
            'success' => true,
            'message_body' => $message,
            'provider_response' => $providerResponse,
        ];
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

        $message = $this->renderer->render($template->message_body, $this->appointmentVariables($appointment, $settings));

        $this->createLog($templateKey, $language, $appointment, 'sent', $message, json_encode([
            'provider' => $settings->whatsapp_provider ?: 'log',
            'base_url' => $settings->whatsapp_api_base_url,
        ], JSON_UNESCAPED_UNICODE));

        return true;
    }

    protected function appointmentVariables(Appointment $appointment, Setting $settings): array
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

    protected function consultationVariables(?Consultation $consultation, Setting $settings): array
    {
        return [
            'client_name' => $consultation?->full_name ?: 'Client',
            'service_name' => '',
            'appointment_date' => '',
            'appointment_time' => '',
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
            'sent_at' => $status === 'sent' ? now() : null,
            'failed_at' => $status === 'failed' ? now() : null,
            'attempts' => $status === 'sent' ? 1 : 0,
            'automation_source' => $templateKey,
            'provider_response' => $providerResponse,
        ]);
    }
}
