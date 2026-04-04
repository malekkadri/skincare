<?php

namespace App\Http\Controllers\Admin\Ops;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppMessageLog;
use App\Services\BackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HealthController extends Controller
{
    public function index(BackupService $backupService): View
    {
        $checks = [
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'enabled' : 'disabled',
            'queue_connection' => config('queue.default'),
            'scheduler_timezone' => config('app.timezone'),
            'db_connection' => DB::connection()->getDatabaseName(),
            'cache_store' => Cache::getDefaultDriver(),
            'last_whatsapp_activity' => optional(WhatsAppMessageLog::query()->latest('sent_at')->first()?->sent_at)?->toDateTimeString(),
        ];

        return view('admin.ops.health', [
            'checks' => $checks,
            'backups' => $backupService->listBackups(),
        ]);
    }

    public function probe(): JsonResponse
    {
        DB::select('select 1');

        return response()->json([
            'status' => 'ok',
            'app' => config('app.name'),
            'time' => now()->toIso8601String(),
        ]);
    }
}
