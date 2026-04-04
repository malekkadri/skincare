<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\ServiceCategory;
use App\Services\Reports\ServicePerformanceReportService;
use App\Support\Reports\ReportPeriod;
use Illuminate\View\View;

class ServicePerformanceReportController extends Controller
{
    public function __invoke(ReportFilterRequest $request, ServicePerformanceReportService $service): View
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.services', [
            'filters' => $filters,
            'period' => $period,
            'summary' => $service->summary($filters, $period),
            'categories' => ServiceCategory::query()->ordered()->get(['id', 'name_en']),
        ]);
    }
}
