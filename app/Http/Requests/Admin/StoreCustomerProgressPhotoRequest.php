<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerProgressPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->user()?->can('manage_appointments');
    }

    public function rules(): array
    {
        return [
            'photo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'captured_on' => ['nullable', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
