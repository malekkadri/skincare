@extends('admin.layouts.app')

@section('title', 'WhatsApp Logs')
@section('header', 'WhatsApp Logs')

@section('content')
<div class="card">
    <h2>Filters</h2>
    <form method="GET" class="grid">
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? null) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Automation Source</label>
            <select name="automation_source">
                <option value="">All</option>
                @foreach($sources as $source)
                    <option value="{{ $source }}" @selected(($filters['automation_source'] ?? null) === $source)>{{ $source }}</option>
                @endforeach
            </select>
        </div>
        <div><label>Appointment ID</label><input type="number" name="appointment_id" value="{{ $filters['appointment_id'] ?? '' }}"></div>
        <div><label>Consultation ID</label><input type="number" name="consultation_id" value="{{ $filters['consultation_id'] ?? '' }}"></div>
        <div><label>Date From</label><input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"></div>
        <div><label>Date To</label><input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"></div>
        <div><button class="btn" type="submit">Apply</button></div>
    </form>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Created</th>
                <th>Recipient</th>
                <th>Template</th>
                <th>Source</th>
                <th>Lang</th>
                <th>Status</th>
                <th>Attempts</th>
                <th>Scheduled</th>
                <th>Sent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->timezone('Africa/Tunis')->format('Y-m-d H:i') }}</td>
                    <td>{{ $log->recipient_phone ?? '-' }}</td>
                    <td>{{ $log->template_key }}</td>
                    <td>{{ $log->automation_source ?? '-' }}</td>
                    <td>{{ strtoupper($log->language) }}</td>
                    <td>{{ $log->status }}</td>
                    <td>{{ $log->attempts }}</td>
                    <td>{{ $log->scheduled_for?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>{{ $log->sent_at?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.whatsapp.logs.show', $log) }}" class="btn btn-secondary">View</a>
                        @if($log->status === 'failed')
                            <form action="{{ route('admin.whatsapp.logs.retry', $log) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button class="btn btn-success" type="submit">Retry</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="muted">No logs found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $logs->links() }}
</div>
@endsection
