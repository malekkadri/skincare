<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'preferred_language' => ['required', Rule::in(['fr', 'en'])],
            'age_range' => ['nullable', 'string', 'max:50'],
            'skin_type' => ['nullable', 'string', 'max:100'],
            'skin_sensitivity_level' => ['nullable', 'string', 'max:100'],
            'main_concerns' => ['required', 'string', 'max:2000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'current_products' => ['nullable', 'string', 'max:1500'],
            'current_treatments_or_medications' => ['nullable', 'string', 'max:1500'],
            'pregnancy_or_breastfeeding_status' => ['nullable', 'string', 'max:120'],
            'preferred_goals' => ['nullable', 'string', 'max:1500'],
            'face_images' => ['nullable', 'array', 'max:3'],
            'face_images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ];
    }
}
