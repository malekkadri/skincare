<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\WhatsAppMessageLog;
use App\Services\Reports\WhatsAppReportService;
use App\Support\Reports\ReportPeriod;
use Illuminate\View\View;

class WhatsAppReportController extends Controller
{
    public function __invoke(ReportFilterRequest $request, WhatsAppReportService $service): View
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.whatsapp', [
            'filters' => $filters,
            'period' => $period,
            'summary' => $service->summary($filters, $period),
            'logs' => $service->query($filters, $period)->latest()->paginate(20)->withQueryString(),
            'whatsappStatuses' => ['pending', 'processing', 'sent', 'failed', 'skipped'],
            'templateKeys' => WhatsAppMessageLog::query()->select('template_key')->distinct()->pluck('template_key')->filter()->values(),
            'automationSources' => WhatsAppMessageLog::query()->select('automation_source')->distinct()->pluck('automation_source')->filter()->values(),
        ]);
    }
}
