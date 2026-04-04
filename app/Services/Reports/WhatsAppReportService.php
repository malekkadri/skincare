<?php

namespace App\Services\Reports;

use App\Models\WhatsAppMessageLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class WhatsAppReportService
{
    public function query(array $filters, array $period): Builder
    {
        return WhatsAppMessageLog::query()
            ->with(['appointment', 'consultation', 'customer'])
            ->whereBetween('created_at', [$period['start']->utc(), $period['end']->utc()])
            ->when($filters['whatsapp_status'] ?? null, fn (Builder $q, $value) => $q->where('status', $value))
            ->when($filters['template_key'] ?? null, fn (Builder $q, $value) => $q->where('template_key', $value))
            ->when($filters['automation_source'] ?? null, fn (Builder $q, $value) => $q->where('automation_source', $value))
            ->when($filters['language'] ?? null, fn (Builder $q, $value) => $q->where('language', $value));
    }

    public function summary(array $filters, array $period): array
    {
        $query = $this->query($filters, $period);

        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $sent = (int) ($statusCounts['sent'] ?? 0);
        $failed = (int) ($statusCounts['failed'] ?? 0);
        $total = array_sum($statusCounts);

        return [
            'total_messages' => $total,
            'status_counts' => $statusCounts,
            'delivery_success_rate' => ($sent + $failed) > 0 ? round(($sent / ($sent + $failed)) * 100, 2) : 0,
            'top_templates' => (clone $query)->selectRaw('template_key, COUNT(*) as total')->groupBy('template_key')->orderByDesc('total')->limit(10)->get(),
            'top_sources' => (clone $query)->selectRaw('automation_source, COUNT(*) as total')->whereNotNull('automation_source')->groupBy('automation_source')->orderByDesc('total')->limit(10)->get(),
            'failures_over_time' => (clone $query)->where('status', 'failed')->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupBy('day')->orderBy('day')->get(),
            'reminder_vs_followup' => (clone $query)
                ->selectRaw("CASE WHEN automation_source LIKE '%followup%' THEN 'followup' WHEN automation_source LIKE '%reminder%' THEN 'reminder' ELSE 'other' END as type, COUNT(*) as total")
                ->groupBy('type')
                ->get(),
        ];
    }

    public function exportRows(array $filters, array $period): Collection
    {
        return $this->query($filters, $period)->latest()->get();
    }
}
