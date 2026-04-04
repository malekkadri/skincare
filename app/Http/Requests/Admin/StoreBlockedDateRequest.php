<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlockedDateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blocked_date' => ['required', 'date_format:Y-m-d', Rule::unique('blocked_dates', 'blocked_date')],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
