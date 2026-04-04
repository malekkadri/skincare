<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline_fr' => ['nullable', 'string', 'max:255'],
            'site_tagline_en' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,svg,webp', 'max:1024'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'address_fr' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'default_language' => ['required', Rule::in(['fr', 'en'])],
            'supported_languages' => ['required', 'array', 'min:1'],
            'supported_languages.*' => ['required', Rule::in(['fr', 'en'])],
            'default_currency' => ['required', Rule::in(['TND', 'EUR'])],
            'supported_currencies' => ['required', 'array', 'min:1'],
            'supported_currencies.*' => ['required', Rule::in(['TND', 'EUR'])],
            'timezone' => ['required', 'timezone'],
            'hero_title_fr' => ['nullable', 'string', 'max:255'],
            'hero_title_en' => ['nullable', 'string', 'max:255'],
            'hero_subtitle_fr' => ['nullable', 'string'],
            'hero_subtitle_en' => ['nullable', 'string'],
            'hero_button_text_fr' => ['nullable', 'string', 'max:255'],
            'hero_button_text_en' => ['nullable', 'string', 'max:255'],
        ];
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $languages = (array) $this->input('supported_languages', []);
            $currencies = (array) $this->input('supported_currencies', []);

            if (! in_array($this->input('default_language'), $languages, true)) {
                $validator->errors()->add('default_language', 'Default language must be included in supported languages.');
            }

            if (! in_array($this->input('default_currency'), $currencies, true)) {
                $validator->errors()->add('default_currency', 'Default currency must be included in supported currencies.');
            }
        });
    }

}
