<?php

namespace App\Http\Requests\Admin;

use App\Models\WhatsAppTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWhatsAppTemplateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'key' => ['required', Rule::in(WhatsAppTemplate::KEYS)],
            'language' => ['required', Rule::in(['fr', 'en'])],
            'message_body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
