@extends('admin.layouts.app')
@section('title', 'Consultations Report')
@section('header', 'Customer & Consultation Insights')
@section('content')
@include('admin.reports.partials.nav')
@include('admin.reports.partials.filters')
<div class="toolbar" style="margin-bottom:1rem"><a class="btn" href="{{ route('admin.reports.exports.consultations', request()->query()) }}">Export CSV</a></div>
<div class="kpi-grid">
    <div class="card kpi"><div class="label">New Customers</div><div class="value">{{ $summary['new_customers'] }}</div></div>
    <div class="card kpi"><div class="label">Repeat Customers</div><div class="value">{{ $summary['repeat_customers'] }}</div></div>
    <div class="card kpi"><div class="label">Consultations</div><div class="value">{{ $summary['consultations_submitted'] }}</div></div>
    <div class="card kpi"><div class="label">Conversion Rate</div><div class="value">{{ $summary['consultation_conversion_rate'] }}%</div></div>
</div>
<div class="card"><h2>Consultations</h2><table class="table"><thead><tr><th>Date</th><th>Name</th><th>Status</th><th>Language</th><th>Main Concerns</th></tr></thead><tbody>@foreach($consultations as $consultation)<tr><td>{{ $consultation->created_at?->timezone('Africa/Tunis')->format('Y-m-d') }}</td><td>{{ $consultation->full_name }}</td><td>{{ $consultation->status }}</td><td>{{ strtoupper($consultation->preferred_language) }}</td><td>{{ \Illuminate\Support\Str::limit($consultation->main_concerns, 90) }}</td></tr>@endforeach</tbody></table>{{ $consultations->links() }}</div>
@endsection
