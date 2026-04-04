<?php

namespace App\Http\Requests\Admin;

use App\Models\Consultation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Consultation::STATUSES)],
            'admin_notes' => ['nullable', 'string', 'max:4000'],
        ];
    }
}
