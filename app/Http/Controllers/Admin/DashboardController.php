<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Reports\DashboardReportService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(DashboardReportService $reports): View
    {
        return view('admin.dashboard', [
            'dashboard' => $reports->build('Africa/Tunis'),
            'settings' => Setting::current(),
        ]);
    }
}
