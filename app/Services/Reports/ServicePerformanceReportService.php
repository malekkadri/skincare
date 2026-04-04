<?php

namespace App\Services\Reports;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServicePerformanceReportService
{
    public function summary(array $filters, array $period): array
    {
        $rows = Service::query()
            ->leftJoin('appointments', function ($join) use ($period, $filters) {
                $join->on('appointments.service_id', '=', 'services.id')
                    ->whereBetween('appointments.appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()]);

                if ($filters['language'] ?? null) {
                    $join->where('appointments.preferred_language', $filters['language']);
                }
                if ($filters['currency'] ?? null) {
                    $join->where('appointments.booked_currency', $filters['currency']);
                }
            })
            ->when($filters['category_id'] ?? null, fn ($q, $value) => $q->where('services.category_id', $value))
            ->selectRaw('services.id, services.name_en, services.is_featured, COUNT(appointments.id) as total_bookings')
            ->selectRaw("SUM(CASE WHEN appointments.status = 'completed' THEN 1 ELSE 0 END) as completed_bookings")
            ->selectRaw("SUM(CASE WHEN appointments.status = 'cancelled' THEN 1 ELSE 0 END) as cancellations")
            ->selectRaw("SUM(CASE WHEN appointments.status = 'no_show' THEN 1 ELSE 0 END) as no_shows")
            ->selectRaw("SUM(CASE WHEN appointments.status = 'completed' AND appointments.booked_currency = 'TND' THEN appointments.booked_price ELSE 0 END) as revenue_tnd")
            ->selectRaw("SUM(CASE WHEN appointments.status = 'completed' AND appointments.booked_currency = 'EUR' THEN appointments.booked_price ELSE 0 END) as revenue_eur")
            ->selectRaw("AVG(CASE WHEN appointments.status = 'completed' THEN appointments.booked_price END) as avg_booking_value")
            ->selectRaw('MAX(appointments.appointment_date) as last_booked_date')
            ->groupBy('services.id', 'services.name_en', 'services.is_featured')
            ->orderByDesc('total_bookings')
            ->get();

        return [
            'services' => $rows,
            'most_booked' => $rows->sortByDesc('total_bookings')->take(5)->values(),
            'highest_revenue' => $rows->sortByDesc(fn ($row) => (float) $row->revenue_tnd + (float) $row->revenue_eur)->take(5)->values(),
            'highest_cancellation' => $rows->sortByDesc('cancellations')->take(5)->values(),
        ];
    }
}
