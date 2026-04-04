<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRecommendationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_language' => ['required', Rule::in(['fr', 'en'])],
            'skin_type' => ['nullable', 'string', 'max:100'],
            'skin_sensitivity_level' => ['nullable', 'string', 'max:100'],
            'main_concerns' => ['required', 'string', 'max:2000'],
            'preferred_goals' => ['nullable', 'string', 'max:1500'],
            'face_images' => ['nullable', 'array', 'max:3'],
            'face_images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:8192', 'dimensions:min_width=300,min_height=300,max_width=6000,max_height=6000'],
        ];
    }
}
