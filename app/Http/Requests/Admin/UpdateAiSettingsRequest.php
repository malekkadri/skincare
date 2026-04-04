<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAiSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ai_enabled' => ['nullable', 'boolean'],
            'ai_provider' => ['required', Rule::in(['grok'])],
            'ai_api_key' => ['nullable', 'string', 'max:500'],
            'ai_model' => ['nullable', 'string', 'max:120'],
            'ai_base_url' => ['nullable', 'url', 'max:255'],
            'ai_temperature' => ['nullable', 'numeric', 'between:0,1'],
            'ai_timeout_seconds' => ['nullable', 'integer', 'min:5', 'max:120'],
            'ai_enable_consultation_summary' => ['nullable', 'boolean'],
            'ai_enable_service_recommendation' => ['nullable', 'boolean'],
            'ai_enable_admin_content_helper' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->boolean('ai_enabled') && ! $this->filled('ai_api_key')) {
                $validator->errors()->add('ai_api_key', 'API key is required when AI is enabled.');
            }
        });
    }
}
