<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsAppSettingsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'whatsapp_enabled' => ['nullable', 'boolean'],
            'whatsapp_provider' => ['nullable', 'string', 'max:50'],
            'whatsapp_business_number' => ['nullable', 'string', 'max:50'],
            'whatsapp_api_key' => ['nullable', 'string', 'max:2048'],
            'whatsapp_api_base_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_default_country_code' => ['required', 'string', 'max:6'],
            'send_booking_confirmation_whatsapp' => ['nullable', 'boolean'],
            'send_booking_cancellation_whatsapp' => ['nullable', 'boolean'],
            'send_booking_reschedule_whatsapp' => ['nullable', 'boolean'],

            'whatsapp_automation_enabled' => ['nullable', 'boolean'],
            'send_24h_reminder' => ['nullable', 'boolean'],
            'send_2h_reminder' => ['nullable', 'boolean'],
            'send_post_appointment_followup' => ['nullable', 'boolean'],
            'send_consultation_acknowledgement' => ['nullable', 'boolean'],
            'reminder_24h_lead_minutes' => ['required', 'integer', 'min:5', 'max:10080'],
            'reminder_2h_lead_minutes' => ['required', 'integer', 'min:5', 'max:1440'],
            'followup_lead_minutes' => ['required', 'integer', 'min:5', 'max:20160'],
            'max_whatsapp_retry_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'whatsapp_retry_backoff_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'automation_pause_until' => ['nullable', 'date'],
        ];
    }
}
