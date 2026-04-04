<?php

namespace App\Services\Reports;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AppointmentReportService
{
    public function query(array $filters, array $period): Builder
    {
        return Appointment::query()
            ->with(['customer', 'service.category'])
            ->whereBetween('appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->when($filters['appointment_status'] ?? null, fn (Builder $q, $value) => $q->where('status', $value))
            ->when($filters['service_id'] ?? null, fn (Builder $q, $value) => $q->where('service_id', $value))
            ->when($filters['category_id'] ?? null, fn (Builder $q, $value) => $q->whereHas('service', fn (Builder $serviceQ) => $serviceQ->where('category_id', $value)))
            ->when($filters['language'] ?? null, fn (Builder $q, $value) => $q->where('preferred_language', $value))
            ->when($filters['currency'] ?? null, fn (Builder $q, $value) => $q->where('booked_currency', $value));
    }

    public function summary(array $filters, array $period): array
    {
        $query = $this->query($filters, $period);
        $total = (clone $query)->count();

        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $completed = (int) ($statusCounts[Appointment::STATUS_COMPLETED] ?? 0);
        $cancelled = (int) ($statusCounts[Appointment::STATUS_CANCELLED] ?? 0);

        $busiestDays = (clone $query)
            ->selectRaw('appointment_date, COUNT(*) as total')
            ->groupBy('appointment_date')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $busiestSlots = (clone $query)
            ->selectRaw('start_time, COUNT(*) as total')
            ->groupBy('start_time')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $commonServices = (clone $query)
            ->leftJoin('services', 'services.id', '=', 'appointments.service_id')
            ->selectRaw('appointments.service_id, COALESCE(services.name_en, appointments.service_name_snapshot_en) as service_name, COUNT(*) as total')
            ->groupBy('appointments.service_id', 'service_name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $daysInPeriod = max(1, $period['start']->diffInDays($period['end']) + 1);

        return [
            'total' => $total,
            'status_counts' => $statusCounts,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            'cancellation_rate' => $total > 0 ? round(($cancelled / $total) * 100, 2) : 0,
            'busiest_days' => $busiestDays,
            'busiest_slots' => $busiestSlots,
            'common_services' => $commonServices,
            'average_per_day' => round($total / $daysInPeriod, 2),
        ];
    }

    public function exportRows(array $filters, array $period): Collection
    {
        return $this->query($filters, $period)->latest('appointment_date')->get();
    }
}
