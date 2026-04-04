<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->string('month')->toString();
        $focus = $month ? Carbon::createFromFormat('Y-m', $month, 'Africa/Tunis') : now('Africa/Tunis');

        return view('admin.calendar.index', [
            'focus' => $focus,
            'prevMonth' => $focus->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $focus->copy()->addMonth()->format('Y-m'),
        ]);
    }

    public function events(Request $request): JsonResponse
    {
        $start = Carbon::parse($request->query('start', now('Africa/Tunis')->startOfMonth()->toDateString()), 'Africa/Tunis')->startOfDay();
        $end = Carbon::parse($request->query('end', now('Africa/Tunis')->endOfMonth()->toDateString()), 'Africa/Tunis')->endOfDay();

        $events = Appointment::query()
            ->with(['customer', 'service'])
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get()
            ->map(function (Appointment $appointment): array {
                $startAt = Carbon::parse($appointment->appointment_date->toDateString().' '.$appointment->start_time, 'Africa/Tunis');
                $endAt = Carbon::parse($appointment->appointment_date->toDateString().' '.$appointment->end_time, 'Africa/Tunis');
                return [
                    'id' => $appointment->id,
                    'title' => trim(($appointment->service_name_snapshot_en ?: $appointment->service?->name_en).' - '.($appointment->customer?->full_name ?? 'Client')),
                    'start' => $startAt->toIso8601String(),
                    'end' => $endAt->toIso8601String(),
                    'status' => $appointment->status,
                    'url' => route('admin.appointments.show', $appointment),
                    'edit_url' => route('admin.appointments.edit', $appointment),
                    'className' => 'status-'.$appointment->status,
                ];
            })->values();

        return response()->json(['events' => $events]);
    }
}
