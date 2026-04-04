<?php

namespace Database\Seeders;

use App\Models\BlockedDate;
use App\Models\BlockedTimeRange;
use App\Models\WeeklyBusinessHour;
use Illuminate\Database\Seeder;

class BusinessAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $schedule = [
            0 => [false, null, null],
            1 => [true, '09:00:00', '18:00:00'],
            2 => [true, '09:00:00', '18:00:00'],
            3 => [true, '09:00:00', '18:00:00'],
            4 => [true, '09:00:00', '18:00:00'],
            5 => [true, '09:00:00', '18:00:00'],
            6 => [true, '09:00:00', '15:00:00'],
        ];

        foreach ($schedule as $day => [$isOpen, $start, $end]) {
            WeeklyBusinessHour::query()->updateOrCreate(
                ['day_of_week' => $day],
                ['is_open' => $isOpen, 'start_time' => $start, 'end_time' => $end]
            );
        }

        BlockedDate::query()->updateOrCreate(
            ['blocked_date' => '2026-05-01'],
            ['reason' => 'Labour Day holiday']
        );

        BlockedTimeRange::query()->updateOrCreate(
            ['blocked_date' => '2026-04-15', 'start_time' => '13:00:00', 'end_time' => '15:00:00'],
            ['reason' => 'Team training']
        );
    }
}
