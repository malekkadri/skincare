<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        ];
    }
}
