<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\Reports\ReportPeriod;
use App\Services\Reports\AppointmentReportService;
use Illuminate\View\View;

class AppointmentReportController extends Controller
{
    public function __invoke(ReportFilterRequest $request, AppointmentReportService $service): View
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);

        return view('admin.reports.appointments', [
            'filters' => $filters,
            'period' => $period,
            'summary' => $service->summary($filters, $period),
            'appointments' => $service->query($filters, $period)->latest('appointment_date')->paginate(20)->withQueryString(),
            'services' => Service::query()->ordered()->get(['id', 'name_en']),
            'categories' => ServiceCategory::query()->ordered()->get(['id', 'name_en']),
            'appointmentStatuses' => ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'],
        ]);
    }
}
