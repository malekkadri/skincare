@extends('admin.layouts.app')
@section('title', 'Reports Overview')
@section('header', 'Reports Overview')
@section('content')
    @include('admin.reports.partials.nav')
    @php($appointmentStatuses=$filterMeta['appointmentStatuses'])
    @php($consultationStatuses=$filterMeta['consultationStatuses'])
    @php($whatsappStatuses=$filterMeta['whatsappStatuses'])
    @php($services=$filterMeta['services'])
    @php($categories=$filterMeta['categories'])
    @php($templateKeys=$filterMeta['templateKeys'])
    @php($automationSources=$filterMeta['automationSources'])
    @include('admin.reports.partials.filters')

    <div class="kpi-grid">
        <div class="card kpi"><div class="label">Appointments</div><div class="value">{{ $appointments['total'] }}</div></div>
        <div class="card kpi"><div class="label">Completion Rate</div><div class="value">{{ $appointments['completion_rate'] }}%</div></div>
        <div class="card kpi"><div class="label">Consultation Conversion</div><div class="value">{{ $consultations['consultation_conversion_rate'] }}%</div></div>
        <div class="card kpi"><div class="label">WhatsApp Success</div><div class="value">{{ $whatsapp['delivery_success_rate'] }}%</div></div>
    </div>

    <div class="card">
        <h2>Revenue by Currency</h2>
        <table class="table"><thead><tr><th>Currency</th><th>Total</th><th>Average Booking Value</th><th>Completed Appointments</th></tr></thead><tbody>
            @forelse($revenue['totals_by_currency'] as $item)
                <tr><td>{{ $item->booked_currency }}</td><td>{{ number_format($item->total, 2) }}</td><td>{{ number_format($item->avg_value, 2) }}</td><td>{{ $item->count }}</td></tr>
            @empty <tr><td colspan="4" class="muted">No revenue data for selected range.</td></tr> @endforelse
        </tbody></table>
    </div>
@endsection
