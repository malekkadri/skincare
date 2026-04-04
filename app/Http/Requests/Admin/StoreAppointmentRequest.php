<?php

namespace App\Http\Requests\Admin;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', Rule::exists('services', 'id')->where('is_active', true)],
            'appointment_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'status' => ['required', Rule::in(Appointment::statuses())],
            'booked_currency' => ['required', Rule::in(['TND', 'EUR'])],
            'notes' => ['nullable', 'string'],
            'admin_notes' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer.first_name' => ['required_without:customer_id', 'nullable', 'string', 'max:255'],
            'customer.last_name' => ['nullable', 'string', 'max:255'],
            'customer.phone' => ['required_without:customer_id', 'nullable', 'string', 'max:50'],
            'customer.email' => ['nullable', 'email', 'max:255'],
            'customer.preferred_language' => ['nullable', Rule::in(['fr', 'en'])],
            'customer.preferred_currency' => ['nullable', Rule::in(['TND', 'EUR'])],
            'customer.notes' => ['nullable', 'string'],
        ];
    }
}
