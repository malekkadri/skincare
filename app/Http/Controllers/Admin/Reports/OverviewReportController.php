<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\WhatsAppMessageLog;
use App\Services\Reports\AppointmentReportService;
use App\Services\Reports\ConsultationReportService;
use App\Services\Reports\RevenueReportService;
use App\Services\Reports\ServicePerformanceReportService;
use App\Services\Reports\WhatsAppReportService;
use App\Support\Reports\ReportPeriod;
use Illuminate\View\View;

class OverviewReportController extends Controller
{
    public function __invoke(
        ReportFilterRequest $request,
        AppointmentReportService $appointments,
        RevenueReportService $revenue,
        ServicePerformanceReportService $services,
        ConsultationReportService $consultations,
        WhatsAppReportService $whatsApp,
    ): View {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.overview', [
            'filters' => $filters,
            'period' => $period,
            'filterMeta' => $this->filterMeta(),
            'appointments' => $appointments->summary($filters, $period),
            'revenue' => $revenue->summary($filters, $period),
            'services' => $services->summary($filters, $period),
            'consultations' => $consultations->summary($filters, $period),
            'whatsapp' => $whatsApp->summary($filters, $period),
        ]);
    }

    private function filterMeta(): array
    {
        return [
            'services' => Service::query()->ordered()->get(['id', 'name_en']),
            'categories' => ServiceCategory::query()->ordered()->get(['id', 'name_en']),
            'appointmentStatuses' => ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'],
            'consultationStatuses' => ['new', 'reviewed', 'contacted', 'converted', 'archived'],
            'whatsappStatuses' => ['pending', 'processing', 'sent', 'failed', 'skipped'],
            'templateKeys' => WhatsAppMessageLog::query()->select('template_key')->distinct()->pluck('template_key')->filter()->values(),
            'automationSources' => WhatsAppMessageLog::query()->select('automation_source')->distinct()->pluck('automation_source')->filter()->values(),
        ];
    }
}
