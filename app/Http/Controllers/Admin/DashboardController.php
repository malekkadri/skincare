<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsAppMessageLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now('Africa/Tunis')->toDateString();

        return view('admin.dashboard', [
            'pendingMessages' => WhatsAppMessageLog::query()->whereIn('status', ['pending', 'processing'])->count(),
            'failedMessages' => WhatsAppMessageLog::query()->where('status', 'failed')->count(),
            'queuedToday' => WhatsAppMessageLog::query()->whereDate('created_at', $today)->whereNotNull('automation_source')->count(),
            'sentToday' => WhatsAppMessageLog::query()->whereDate('sent_at', $today)->count(),
            'failedToday' => WhatsAppMessageLog::query()->whereDate('failed_at', $today)->count(),
            'settings' => Setting::current(),
        ]);
    }
}
