<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BlockedDate;
use App\Models\BlockedTimeRange;
use App\Models\Service;
use App\Models\Setting;
use App\Models\WeeklyBusinessHour;
use Carbon\Carbon;

class AvailabilityService
{
    public function getAvailableSlots(Service $service, Carbon|string $date): array
    {
        $day = $this->parseDate($date);
        $settings = Setting::current();

        if (! $settings->booking_enabled || $this->isDateBlocked($day)) {
            return [];
        }

        $maxAhead = (int) $settings->maximum_booking_days_ahead;
        if ($maxAhead > 0 && $day->isAfter(Carbon::now($this->timezone())->addDays($maxAhead))) {
            return [];
        }

        $hours = WeeklyBusinessHour::query()->where('day_of_week', $day->dayOfWeek)->first();
        if (! $hours || ! $hours->is_open || ! $hours->start_time || ! $hours->end_time) {
            return [];
        }

        $openAt = Carbon::parse($day->toDateString().' '.$hours->start_time, $this->timezone());
        $closeAt = Carbon::parse($day->toDateString().' '.$hours->end_time, $this->timezone());
        $blockedRanges = BlockedTimeRange::query()->whereDate('blocked_date', $day->toDateString())->get();
        $existingAppointments = Appointment::query()->onDate($day->toDateString())->activeForAvailability()->get();

        $interval = max(5, (int) $settings->slot_interval_minutes);
        $durationWithBuffer = (int) $service->duration_minutes + (int) $service->buffer_minutes;
        $minimumStart = $day->isSameDay(Carbon::now($this->timezone()))
            ? Carbon::now($this->timezone())->addHours((int) $settings->minimum_notice_hours)
            : $day->copy()->startOfDay();

        $slots = [];
        for ($cursor = $openAt->copy(); $cursor->lt($closeAt); $cursor->addMinutes($interval)) {
            $slotEnd = $cursor->copy()->addMinutes($durationWithBuffer);

            if ($slotEnd->gt($closeAt) || $cursor->lt($minimumStart)) {
                continue;
            }

            if ($this->collidesWithRanges($cursor, $slotEnd, $blockedRanges)
                || $this->collidesWithRanges($cursor, $slotEnd, $existingAppointments, 'start_time', 'end_time')) {
                continue;
            }

            $slots[] = $cursor->format('H:i');
        }

        return $slots;
    }

    public function isDateBlocked(Carbon|string $date): bool
    {
        return BlockedDate::query()->whereDate('blocked_date', $this->parseDate($date)->toDateString())->exists();
    }

    public function hasOverlap(Carbon|string $date, string $startTime, string $endTime, ?int $ignoreAppointmentId = null): bool
    {
        $day = $this->parseDate($date);
        $start = Carbon::parse($day->toDateString().' '.$startTime, $this->timezone());
        $end = Carbon::parse($day->toDateString().' '.$endTime, $this->timezone());

        if ($this->isDateBlocked($day) || $end->lte($start)) {
            return true;
        }

        $hours = WeeklyBusinessHour::query()->where('day_of_week', $day->dayOfWeek)->first();
        if (! $hours || ! $hours->is_open || ! $hours->start_time || ! $hours->end_time) {
            return true;
        }

        $openAt = Carbon::parse($day->toDateString().' '.$hours->start_time, $this->timezone());
        $closeAt = Carbon::parse($day->toDateString().' '.$hours->end_time, $this->timezone());
        if ($start->lt($openAt) || $end->gt($closeAt)) {
            return true;
        }

        $blockedRanges = BlockedTimeRange::query()->whereDate('blocked_date', $day->toDateString())->get();
        if ($this->collidesWithRanges($start, $end, $blockedRanges)) {
            return true;
        }

        $appointments = Appointment::query()
            ->onDate($day->toDateString())
            ->activeForAvailability()
            ->when($ignoreAppointmentId, fn ($query) => $query->whereKeyNot($ignoreAppointmentId))
            ->get();

        return $this->collidesWithRanges($start, $end, $appointments, 'start_time', 'end_time');
    }

    protected function collidesWithRanges(Carbon $start, Carbon $end, $items, string $startField = 'start_time', string $endField = 'end_time'): bool
    {
        foreach ($items as $item) {
            $rangeStart = Carbon::parse($start->toDateString().' '.$item->{$startField}, $this->timezone());
            $rangeEnd = Carbon::parse($start->toDateString().' '.$item->{$endField}, $this->timezone());
            if ($start->lt($rangeEnd) && $end->gt($rangeStart)) {
                return true;
            }
        }

        return false;
    }

    protected function parseDate(Carbon|string $date): Carbon
    {
        return $date instanceof Carbon ? $date->copy()->timezone($this->timezone()) : Carbon::parse($date, $this->timezone());
    }

    protected function timezone(): string
    {
        return Setting::current()->timezone ?: 'Africa/Tunis';
    }
}
