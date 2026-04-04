<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reports\ReportFilterRequest;
use App\Services\Reports\AppointmentReportService;
use App\Services\Reports\ConsultationReportService;
use App\Services\Reports\WhatsAppReportService;
use App\Support\Reports\ReportPeriod;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function appointments(ReportFilterRequest $request, AppointmentReportService $service): StreamedResponse
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);
        $rows = $service->exportRows($filters, $period);

        return $this->streamCsv($this->filename('appointments', $period), ['ID', 'Date', 'Time', 'Customer', 'Service', 'Status', 'Language', 'Currency', 'Booked Price'],
            $rows->map(fn ($row) => [
                $row->id,
                $row->appointment_date?->format('Y-m-d'),
                $row->start_time,
                $row->customer?->full_name,
                $row->service_name_snapshot_en,
                $row->status,
                $row->preferred_language,
                $row->booked_currency,
                $row->booked_price,
            ])->all());
    }

    public function consultations(ReportFilterRequest $request, ConsultationReportService $service): StreamedResponse
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);
        $rows = $service->exportRows($filters, $period);

        return $this->streamCsv($this->filename('consultations', $period), ['ID', 'Created At', 'Name', 'Phone', 'Email', 'Language', 'Status', 'Main Concerns'],
            $rows->map(fn ($row) => [
                $row->id,
                $row->created_at?->timezone('Africa/Tunis')->format('Y-m-d H:i'),
                $row->full_name,
                $row->phone,
                $row->email,
                $row->preferred_language,
                $row->status,
                $row->main_concerns,
            ])->all());
    }

    public function whatsapp(ReportFilterRequest $request, WhatsAppReportService $service): StreamedResponse
    {
        $filters = $request->validated();
        $period = ReportPeriod::resolve($filters);
        $rows = $service->exportRows($filters, $period);

        return $this->streamCsv($this->filename('whatsapp-logs', $period), ['ID', 'Created At', 'Status', 'Template', 'Source', 'Language', 'Recipient', 'Error Code'],
            $rows->map(fn ($row) => [
                $row->id,
                $row->created_at?->timezone('Africa/Tunis')->format('Y-m-d H:i'),
                $row->status,
                $row->template_key,
                $row->automation_source,
                $row->language,
                $row->recipient_phone,
                $row->error_code,
            ])->all());
    }

    private function filename(string $prefix, array $period): string
    {
        return sprintf('%s_%s_to_%s.csv', $prefix, $period['start']->toDateString(), $period['end']->toDateString());
    }

    private function streamCsv(string $filename, array $headers, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
