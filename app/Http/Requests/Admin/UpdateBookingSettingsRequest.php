<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slot_interval_minutes' => ['required', 'integer', 'min:5', 'max:180'],
            'minimum_notice_hours' => ['required', 'integer', 'min:0', 'max:168'],
            'maximum_booking_days_ahead' => ['required', 'integer', 'min:1', 'max:365'],
            'max_appointments_per_day' => ['nullable', 'integer', 'min:1', 'max:500'],
            'booking_enabled' => ['nullable', 'boolean'],
        ];
    }
}
