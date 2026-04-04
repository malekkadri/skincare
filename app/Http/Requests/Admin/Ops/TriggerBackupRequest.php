<?php

namespace App\Http\Requests\Admin\Ops;

use Illuminate\Foundation\Http\FormRequest;

class TriggerBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prefix' => ['nullable', 'string', 'alpha_dash', 'max:40'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (is_array($data) && empty($data['prefix'])) {
            $data['prefix'] = 'database';
        }

        return $data;
    }
}
