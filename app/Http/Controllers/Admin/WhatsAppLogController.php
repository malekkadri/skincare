<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsAppLogFilterRequest;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppMessageLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppLogController extends Controller
{
    public function index(WhatsAppLogFilterRequest $request): View
    {
        $query = WhatsAppMessageLog::query()->with(['appointment', 'consultation', 'customer'])->latest();

        $filters = $request->validated();

        $query->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->when($filters['automation_source'] ?? null, fn ($q, $value) => $q->where('automation_source', $value))
            ->when($filters['appointment_id'] ?? null, fn ($q, $value) => $q->where('appointment_id', $value))
            ->when($filters['consultation_id'] ?? null, fn ($q, $value) => $q->where('related_consultation_id', $value))
            ->when($filters['date_from'] ?? null, fn ($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['date_to'] ?? null, fn ($q, $value) => $q->whereDate('created_at', '<=', $value));

        return view('admin.whatsapp.logs.index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'sources' => WhatsAppMessageLog::query()->select('automation_source')->distinct()->pluck('automation_source')->filter()->values(),
            'statuses' => ['pending', 'processing', 'sent', 'failed', 'skipped'],
            'filters' => $filters,
        ]);
    }

    public function show(WhatsAppMessageLog $log): View
    {
        return view('admin.whatsapp.logs.show', ['log' => $log->load(['appointment', 'consultation', 'customer'])]);
    }

    public function retry(WhatsAppMessageLog $log): RedirectResponse
    {
        SendWhatsAppMessageJob::dispatch($log->id, true);

        return back()->with('success', 'Retry queued successfully.');
    }
}
