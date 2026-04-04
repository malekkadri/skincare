<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingSettingsRequest;
use App\Http\Requests\Admin\UpdateWeeklyBusinessHoursRequest;
use App\Models\Setting;
use App\Models\WeeklyBusinessHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function edit(): View
    {
        $hours = WeeklyBusinessHour::query()->orderBy('day_of_week')->get()->keyBy('day_of_week');

        return view('admin.availability.edit', [
            'hours' => $hours,
            'settings' => Setting::current(),
            'dayNames' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        ]);
    }

    public function updateHours(UpdateWeeklyBusinessHoursRequest $request): RedirectResponse
    {
        foreach ($request->validated('hours') as $dayOfWeek => $hour) {
            WeeklyBusinessHour::query()->updateOrCreate(
                ['day_of_week' => (int) $dayOfWeek],
                [
                    'is_open' => (bool) ($hour['is_open'] ?? false),
                    'start_time' => ! empty($hour['is_open']) ? ($hour['start_time'] ?? null) : null,
                    'end_time' => ! empty($hour['is_open']) ? ($hour['end_time'] ?? null) : null,
                ]
            );
        }

        return back()->with('success', 'Weekly business hours updated.');
    }

    public function updateSettings(UpdateBookingSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['booking_enabled'] = $request->boolean('booking_enabled');
        Setting::current()->update($validated);

        return back()->with('success', 'Booking settings updated.');
    }
}
