<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) auth()->user()?->can('manage_appointments');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'preferred_language' => ['required', 'in:fr,en'],
            'preferred_currency' => ['required', 'in:TND,EUR'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'allergies' => ['nullable', 'string', 'max:5000'],
            'skin_notes' => ['nullable', 'string', 'max:5000'],
            'medical_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
