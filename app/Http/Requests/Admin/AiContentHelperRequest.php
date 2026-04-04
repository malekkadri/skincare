<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiContentHelperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_type' => ['required', Rule::in(['service_description', 'homepage_copy', 'faq_answer', 'promotion', 'testimonial_title', 'gallery_caption'])],
            'language' => ['required', Rule::in(['fr', 'en'])],
            'context' => ['nullable', 'string', 'max:1200'],
            'prompt' => ['required', 'string', 'max:2500'],
        ];
    }
}
