<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeeklyBusinessHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hours' => ['required', 'array', 'size:7'],
            'hours.*.is_open' => ['nullable', 'boolean'],
            'hours.*.start_time' => ['nullable', 'date_format:H:i'],
            'hours.*.end_time' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            foreach ((array) $this->input('hours', []) as $day => $hour) {
                $open = (bool) ($hour['is_open'] ?? false);
                $start = $hour['start_time'] ?? null;
                $end = $hour['end_time'] ?? null;

                if ($open && (! $start || ! $end)) {
                    $validator->errors()->add("hours.{$day}.start_time", 'Open days require start and end time.');
                }

                if ($open && $start && $end && $end <= $start) {
                    $validator->errors()->add("hours.{$day}.end_time", 'End time must be after start time.');
                }
            }
        });
    }
}
