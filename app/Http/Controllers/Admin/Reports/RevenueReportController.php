<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\Reports\RevenueReportService;
use App\Support\Reports\ReportPeriod;
use Illuminate\View\View;

class RevenueReportController extends Controller
{
    public function __invoke(ReportFilterRequest $request, RevenueReportService $service): View
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.revenue', [
            'filters' => $filters,
            'period' => $period,
            'summary' => $service->summary($filters, $period),
            'services' => Service::query()->ordered()->get(['id', 'name_en']),
            'categories' => ServiceCategory::query()->ordered()->get(['id', 'name_en']),
        ]);
    }
}
