<?php

namespace App\Services;

use App\Jobs\SendWhatsAppMessageJob;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Setting;
use App\Models\WhatsAppMessageLog;
use App\Models\WhatsAppTemplate;
use Carbon\Carbon;

class WhatsAppAutomationService
{
    public function queueDueAppointmentReminders(): int
    {
        $settings = Setting::current();
        if (! $this->automationEnabled($settings)) {
            return 0;
        }

        $queued = 0;
        $now = now($settings->timezone ?: 'Africa/Tunis');
        $appointments = Appointment::query()
            ->with('customer')
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->whereDate('appointment_date', '>=', $now->toDateString())
            ->get();

        foreach ($appointments as $appointment) {
            if ($settings->send_24h_reminder) {
                $queued += $this->queueAppointmentMessageIfDue(
                    $appointment,
                    'appointment_reminder_24h',
                    'reminder_24h',
                    (int) $settings->reminder_24h_lead_minutes,
                    $now
                );
            }

            if ($settings->send_2h_reminder) {
                $queued += $this->queueAppointmentMessageIfDue(
                    $appointment,
                    'appointment_reminder_2h',
                    'reminder_2h',
                    (int) $settings->reminder_2h_lead_minutes,
                    $now
                );
            }
        }

        return $queued;
    }

    public function queueDueFollowups(): int
    {
        $settings = Setting::current();
        if (! $this->automationEnabled($settings) || ! $settings->send_post_appointment_followup) {
            return 0;
        }

        $now = now($settings->timezone ?: 'Africa/Tunis');
        $queued = 0;

        $appointments = Appointment::query()
            ->with('customer')
            ->where('status', Appointment::STATUS_COMPLETED)
            ->whereDate('appointment_date', '>=', $now->copy()->subDays(30)->toDateString())
            ->get();

        foreach ($appointments as $appointment) {
            $queued += $this->queueAppointmentMessageIfDue(
                $appointment,
                'appointment_followup',
                'followup',
                -1 * (int) $settings->followup_lead_minutes,
                $now,
                true
            );
        }

        return $queued;
    }

    public function queueConsultationAcknowledgements(): int
    {
        $settings = Setting::current();
        if (! $this->automationEnabled($settings) || ! $settings->send_consultation_acknowledgement) {
            return 0;
        }

        if (! $this->templateIsActive('consultation_acknowledgement', 'fr') && ! $this->templateIsActive('consultation_acknowledgement', 'en')) {
            return 0;
        }

        $queued = 0;
        $consultations = Consultation::query()
            ->with('customer')
            ->where('created_at', '>=', now()->subDays(14))
            ->get();

        foreach ($consultations as $consultation) {
            $source = 'consultation_ack';
            $idempotency = "consultation:{$consultation->id}:{$source}";

            if (WhatsAppMessageLog::query()->where('idempotency_key', $idempotency)->exists()) {
                continue;
            }

            $log = WhatsAppMessageLog::query()->create([
                'customer_id' => $consultation->customer_id,
                'related_consultation_id' => $consultation->id,
                'template_key' => 'consultation_acknowledgement',
                'automation_source' => $source,
                'language' => $consultation->preferred_language ?: 'fr',
                'recipient_phone' => $consultation->phone,
                'status' => 'pending',
                'scheduled_for' => now(),
                'idempotency_key' => $idempotency,
            ]);

            SendWhatsAppMessageJob::dispatch($log->id);
            $queued++;
        }

        return $queued;
    }

    public function shouldSendForAppointment(Appointment $appointment, string $templateKey): bool
    {
        $settings = Setting::current();
        if (! $this->automationEnabled($settings)) {
            return false;
        }

        $statusOk = in_array($appointment->status, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED], true);

        if ($templateKey === 'appointment_followup') {
            $statusOk = $appointment->status === Appointment::STATUS_COMPLETED;
        }

        if (! $statusOk) {
            return false;
        }

        if (! $this->templateIsActive($templateKey, $appointment->preferred_language ?: ($appointment->customer?->preferred_language ?? 'fr'))) {
            return false;
        }

        return true;
    }

    protected function queueAppointmentMessageIfDue(Appointment $appointment, string $templateKey, string $source, int $leadMinutes, Carbon $now, bool $followup = false): int
    {
        if (! $this->shouldSendForAppointment($appointment, $templateKey)) {
            return 0;
        }

        $tz = Setting::current()->timezone ?: 'Africa/Tunis';
        $start = Carbon::parse($appointment->appointment_date->toDateString().' '.$appointment->start_time, $tz);
        $triggerAt = $followup ? $start->copy()->addMinutes(abs($leadMinutes)) : $start->copy()->subMinutes($leadMinutes);

        if (! $followup && $start->lessThanOrEqualTo($now)) {
            return 0;
        }

        if ($triggerAt->greaterThan($now)) {
            return 0;
        }

        $idempotency = "appointment:{$appointment->id}:{$source}";
        if (WhatsAppMessageLog::query()->where('idempotency_key', $idempotency)->exists()) {
            return 0;
        }

        $log = WhatsAppMessageLog::query()->create([
            'appointment_id' => $appointment->id,
            'customer_id' => $appointment->customer_id,
            'template_key' => $templateKey,
            'automation_source' => $source,
            'language' => $appointment->preferred_language ?: ($appointment->customer?->preferred_language ?? 'fr'),
            'recipient_phone' => $appointment->customer?->phone,
            'status' => 'pending',
            'scheduled_for' => $triggerAt,
            'idempotency_key' => $idempotency,
        ]);

        SendWhatsAppMessageJob::dispatch($log->id);

        return 1;
    }

    protected function automationEnabled(Setting $settings): bool
    {
        if (! $settings->whatsapp_enabled || ! $settings->whatsapp_automation_enabled) {
            return false;
        }

        if ($settings->automation_pause_until && $settings->automation_pause_until->isFuture()) {
            return false;
        }

        return true;
    }

    protected function templateIsActive(string $templateKey, string $language): bool
    {
        return WhatsAppTemplate::query()
            ->active()
            ->where('key', $templateKey)
            ->where('language', $language)
            ->exists();
    }
}
