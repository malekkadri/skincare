<?php

namespace App\Http\Controllers\Admin\Ops;

use App\Http\Controllers\Controller;
use App\Services\Ops\LaunchReadinessService;
use Illuminate\View\View;

class LaunchReadinessController extends Controller
{
    public function index(LaunchReadinessService $launchReadinessService): View
    {
        $checks = $launchReadinessService->checks();

        return view('admin.ops.launch-readiness', [
            'checks' => $checks,
            'summary' => $launchReadinessService->summary($checks),
        ]);
    }
}
