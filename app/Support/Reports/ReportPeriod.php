<?php

namespace App\Support\Reports;

use Carbon\Carbon;

class ReportPeriod
{
    public static function resolve(array $filters, string $timezone = 'Africa/Tunis'): array
    {
        $preset = $filters['date_preset'] ?? 'last_7_days';
        $now = now($timezone);

        if (($filters['date_from'] ?? null) && ($filters['date_to'] ?? null)) {
            $start = Carbon::parse($filters['date_from'], $timezone)->startOfDay();
            $end = Carbon::parse($filters['date_to'], $timezone)->endOfDay();

            return ['date_preset' => 'custom', 'start' => $start, 'end' => $end];
        }

        return match ($preset) {
            'today' => ['date_preset' => 'today', 'start' => $now->copy()->startOfDay(), 'end' => $now->copy()->endOfDay()],
            'this_month' => ['date_preset' => 'this_month', 'start' => $now->copy()->startOfMonth(), 'end' => $now->copy()->endOfMonth()],
            'last_month' => ['date_preset' => 'last_month', 'start' => $now->copy()->subMonthNoOverflow()->startOfMonth(), 'end' => $now->copy()->subMonthNoOverflow()->endOfMonth()],
            default => ['date_preset' => 'last_7_days', 'start' => $now->copy()->subDays(6)->startOfDay(), 'end' => $now->copy()->endOfDay()],
        };
    }
}
