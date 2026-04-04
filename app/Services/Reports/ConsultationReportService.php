<?php

namespace App\Services\Reports;

use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ConsultationReportService
{
    public function consultationQuery(array $filters, array $period): Builder
    {
        return Consultation::query()
            ->with('customer')
            ->whereBetween('created_at', [$period['start']->utc(), $period['end']->utc()])
            ->when($filters['consultation_status'] ?? null, fn (Builder $q, $value) => $q->where('status', $value))
            ->when($filters['language'] ?? null, fn (Builder $q, $value) => $q->where('preferred_language', $value));
    }

    public function summary(array $filters, array $period): array
    {
        $consultations = $this->consultationQuery($filters, $period);

        $submitted = (clone $consultations)->count();
        $converted = (clone $consultations)->where('status', 'converted')->count();

        $newCustomers = Customer::query()->whereBetween('created_at', [$period['start']->utc(), $period['end']->utc()])->count();

        $repeatCustomers = Customer::query()->whereHas('appointments', fn (Builder $q) => $q->whereBetween('appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()]))
            ->withCount(['appointments as bookings_count' => fn ($q) => $q->whereBetween('appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()])])
            ->having('bookings_count', '>', 1)
            ->count();

        $topCustomers = Customer::query()
            ->withCount(['appointments as bookings_count' => fn ($q) => $q->whereBetween('appointment_date', [$period['start']->toDateString(), $period['end']->toDateString()])])
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        $commonConcerns = (clone $consultations)
            ->selectRaw('main_concerns, COUNT(*) as total')
            ->groupBy('main_concerns')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $recommendedServicesFrequency = $this->recommendedServicesFrequency($period);

        return [
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
            'consultations_submitted' => $submitted,
            'consultation_conversion_count' => $converted,
            'consultation_conversion_rate' => $submitted > 0 ? round(($converted / $submitted) * 100, 2) : 0,
            'top_customers' => $topCustomers,
            'common_concerns' => $commonConcerns,
            'recommended_services_frequency' => $recommendedServicesFrequency,
        ];
    }

    public function exportRows(array $filters, array $period): Collection
    {
        return $this->consultationQuery($filters, $period)->latest()->get();
    }

    protected function recommendedServicesFrequency(array $period): Collection
    {
        $results = ConsultationAiResult::query()
            ->whereBetween('created_at', [$period['start']->utc(), $period['end']->utc()])
            ->whereNotNull('recommended_services_json')
            ->get(['recommended_services_json']);

        $flattened = collect();
        foreach ($results as $result) {
            $items = collect($result->recommended_services_json)->map(function ($item) {
                if (is_array($item)) {
                    return $item['name'] ?? $item['service'] ?? null;
                }

                return $item;
            })->filter();

            $flattened = $flattened->merge($items);
        }

        return $flattened
            ->countBy()
            ->sortDesc()
            ->take(8)
            ->map(fn ($count, $service) => ['service' => $service, 'total' => $count])
            ->values();
    }
}
