@extends('admin.layouts.app')
@section('title', 'WhatsApp Report')
@section('header', 'WhatsApp Analytics')
@section('content')
@include('admin.reports.partials.nav')
@include('admin.reports.partials.filters')
<div class="toolbar" style="margin-bottom:1rem"><a class="btn" href="{{ route('admin.reports.exports.whatsapp', request()->query()) }}">Export CSV</a></div>
<div class="kpi-grid">
    <div class="card kpi"><div class="label">Total Messages</div><div class="value">{{ $summary['total_messages'] }}</div></div>
    <div class="card kpi"><div class="label">Success Rate</div><div class="value">{{ $summary['delivery_success_rate'] }}%</div></div>
</div>
<div class="card"><h2>Status Counts</h2>@foreach($summary['status_counts'] as $status=>$count)<span class="pill">{{ $status }}: {{ $count }}</span> @endforeach</div>
<div class="card"><h2>WhatsApp Logs</h2><table class="table"><thead><tr><th>Date</th><th>Status</th><th>Template</th><th>Source</th><th>Recipient</th></tr></thead><tbody>@foreach($logs as $log)<tr><td>{{ $log->created_at?->timezone('Africa/Tunis')->format('Y-m-d H:i') }}</td><td>{{ $log->status }}</td><td>{{ $log->template_key }}</td><td>{{ $log->automation_source }}</td><td>{{ $log->recipient_phone }}</td></tr>@endforeach</tbody></table>{{ $logs->links() }}</div>
@endsection
