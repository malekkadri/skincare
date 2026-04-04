@extends('admin.layouts.app')
@section('title', 'Appointments Report')
@section('header', 'Appointments Report')
@section('content')
@include('admin.reports.partials.nav')
@include('admin.reports.partials.filters')
<div class="toolbar" style="margin-bottom:1rem"><a class="btn" href="{{ route('admin.reports.exports.appointments', request()->query()) }}">Export CSV</a></div>
<div class="kpi-grid">
    <div class="card kpi"><div class="label">Total</div><div class="value">{{ $summary['total'] }}</div></div>
    <div class="card kpi"><div class="label">Completion Rate</div><div class="value">{{ $summary['completion_rate'] }}%</div></div>
    <div class="card kpi"><div class="label">Cancellation Rate</div><div class="value">{{ $summary['cancellation_rate'] }}%</div></div>
    <div class="card kpi"><div class="label">Avg / Day</div><div class="value">{{ $summary['average_per_day'] }}</div></div>
</div>
<div class="card"><h2>Status Distribution</h2>@foreach($summary['status_counts'] as $status=>$count)<span class="pill">{{ $status }}: {{ $count }}</span> @endforeach</div>
<div class="card"><h2>Appointments</h2><table class="table"><thead><tr><th>Date</th><th>Time</th><th>Customer</th><th>Service</th><th>Status</th><th>Amount</th></tr></thead><tbody>@foreach($appointments as $appointment)<tr><td>{{ $appointment->appointment_date?->format('Y-m-d') }}</td><td>{{ $appointment->start_time }}</td><td>{{ $appointment->customer?->full_name }}</td><td>{{ $appointment->service_name_snapshot_en }}</td><td>{{ $appointment->status }}</td><td>{{ $appointment->booked_price }} {{ $appointment->booked_currency }}</td></tr>@endforeach</tbody></table>{{ $appointments->links() }}</div>
@endsection
