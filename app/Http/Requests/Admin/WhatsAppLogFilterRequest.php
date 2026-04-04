<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WhatsAppLogFilterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'max:50'],
            'automation_source' => ['nullable', 'string', 'max:100'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
            'consultation_id' => ['nullable', 'integer', 'exists:consultations,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
