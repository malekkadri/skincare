<?php

namespace App\Services\Reports;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Service;
use App\Models\WhatsAppMessageLog;
use Illuminate\Support\Facades\DB;

class DashboardReportService
{
    public function build(string $timezone = 'Africa/Tunis'): array
    {
        $today = now($timezone);
        $todayDate = $today->toDateString();

        $kpis = [
            'appointments_today' => Appointment::query()->whereDate('appointment_date', $todayDate)->count(),
            'appointments_week' => Appointment::query()->whereBetween('appointment_date', [$today->copy()->startOfWeek()->toDateString(), $today->copy()->endOfWeek()->toDateString()])->count(),
            'appointments_month' => Appointment::query()->whereBetween('appointment_date', [$today->copy()->startOfMonth()->toDateString(), $today->copy()->endOfMonth()->toDateString()])->count(),
            'confirmed_upcoming' => Appointment::query()->where('status', Appointment::STATUS_CONFIRMED)->whereDate('appointment_date', '>=', $todayDate)->count(),
            'completed_month' => Appointment::query()->where('status', Appointment::STATUS_COMPLETED)->whereBetween('appointment_date', [$today->copy()->startOfMonth()->toDateString(), $today->copy()->endOfMonth()->toDateString()])->count(),
            'cancelled_month' => Appointment::query()->where('status', Appointment::STATUS_CANCELLED)->whereBetween('appointment_date', [$today->copy()->startOfMonth()->toDateString(), $today->copy()->endOfMonth()->toDateString()])->count(),
            'consultations_month' => Consultation::query()->whereBetween('created_at', [$today->copy()->startOfMonth()->utc(), $today->copy()->endOfMonth()->utc()])->count(),
            'consultations_pending' => Consultation::query()->whereIn('status', ['new', 'reviewed'])->count(),
            'whatsapp_sent_today' => WhatsAppMessageLog::query()->whereDate('sent_at', $todayDate)->count(),
            'whatsapp_failed_today' => WhatsAppMessageLog::query()->whereDate('failed_at', $todayDate)->count(),
        ];

        $appointmentsByDay = Appointment::query()
            ->selectRaw('appointment_date as day, COUNT(*) as total')
            ->whereBetween('appointment_date', [$today->copy()->subDays(29)->toDateString(), $todayDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $revenueTrend = Appointment::query()
            ->selectRaw('appointment_date as day, booked_currency, SUM(booked_price) as total')
            ->where('status', Appointment::STATUS_COMPLETED)
            ->whereBetween('appointment_date', [$today->copy()->subDays(29)->toDateString(), $todayDate])
            ->groupBy('day', 'booked_currency')
            ->orderBy('day')
            ->get();

        $topServices = Appointment::query()
            ->leftJoin('services', 'services.id', '=', 'appointments.service_id')
            ->selectRaw('appointments.service_id, COALESCE(services.name_en, appointments.service_name_snapshot_en) as service_name, COUNT(*) as total')
            ->groupBy('appointments.service_id', 'service_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $statusDistribution = Appointment::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return compact('kpis', 'appointmentsByDay', 'revenueTrend', 'topServices', 'statusDistribution');
    }
}
