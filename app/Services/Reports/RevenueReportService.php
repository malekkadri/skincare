<?php

namespace App\Services\Reports;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class RevenueReportService
{
    public function query(array $filters, array $period): Builder
    {
        return Appointment::query()
            ->where('status', Appointment::STATUS_COMPLETED)
            ->whereBetween('appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()])
            ->when($filters['service_id'] ?? null, fn (Builder $q, $value) => $q->where('service_id', $value))
            ->when($filters['category_id'] ?? null, fn (Builder $q, $value) => $q->whereHas('service', fn (Builder $serviceQ) => $serviceQ->where('category_id', $value)))
            ->when($filters['currency'] ?? null, fn (Builder $q, $value) => $q->where('booked_currency', $value));
    }

    public function summary(array $filters, array $period): array
    {
        $query = $this->query($filters, $period);

        $totalsByCurrency = (clone $query)
            ->selectRaw('booked_currency, SUM(booked_price) as total, AVG(booked_price) as avg_value, COUNT(*) as count')
            ->groupBy('booked_currency')
            ->get();

        $topRevenueServices = (clone $query)
            ->leftJoin('services', 'services.id', '=', 'appointments.service_id')
            ->selectRaw('appointments.service_id, COALESCE(services.name_en, appointments.service_name_snapshot_en) as service_name, booked_currency, SUM(booked_price) as total')
            ->groupBy('appointments.service_id', 'service_name', 'booked_currency')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $groupFormat = match ($filters['trend_group'] ?? 'day') {
            'week' => '%x-W%v',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $trend = (clone $query)
            ->selectRaw("DATE_FORMAT(appointment_date, '{$groupFormat}') as bucket, booked_currency, SUM(booked_price) as total")
            ->groupBy('bucket', 'booked_currency')
            ->orderBy('bucket')
            ->get();

        return [
            'totals_by_currency' => $totalsByCurrency,
            'top_revenue_services' => $topRevenueServices,
            'trend' => $trend,
            'assumption' => 'Realized revenue includes only appointments with completed status. Currencies are reported separately without conversion.',
        ];
    }
}
