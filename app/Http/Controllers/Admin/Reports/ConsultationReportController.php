<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\Consultation;
use App\Services\Reports\ConsultationReportService;
use App\Support\Reports\ReportPeriod;
use Illuminate\View\View;

class ConsultationReportController extends Controller
{
    public function __invoke(ReportFilterRequest $request, ConsultationReportService $service): View
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.consultations', [
            'filters' => $filters,
            'period' => $period,
            'summary' => $service->summary($filters, $period),
            'consultations' => $service->consultationQuery($filters, $period)->latest()->paginate(20)->withQueryString(),
            'consultationStatuses' => Consultation::STATUSES,
        ]);
    }
}
