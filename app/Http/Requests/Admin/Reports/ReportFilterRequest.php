<?php

namespace App\Http\Requests\Admin\Reports;

use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_preset' => ['nullable', Rule::in(['today', 'last_7_days', 'this_month', 'last_month', 'custom'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'appointment_status' => ['nullable', Rule::in(Appointment::statuses())],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'language' => ['nullable', Rule::in(['fr', 'en'])],
            'currency' => ['nullable', Rule::in(['TND', 'EUR'])],
            'consultation_status' => ['nullable', Rule::in(Consultation::STATUSES)],
            'whatsapp_status' => ['nullable', Rule::in(['pending', 'processing', 'sent', 'failed', 'skipped'])],
            'template_key' => ['nullable', 'string', 'max:120'],
            'automation_source' => ['nullable', 'string', 'max:120'],
            'trend_group' => ['nullable', Rule::in(['day', 'week', 'month'])],
        ];
    }
}
