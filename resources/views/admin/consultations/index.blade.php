@extends('admin.layouts.app')
@section('title', 'Consultations')
@section('header', 'Consultations')
@section('content')
@php
    $aiBadge = fn (?string $status) => match($status) {
        'completed' => 'status-confirmed',
        'failed' => 'status-cancelled',
        'processing' => 'status-pending',
        'pending' => 'status-pending',
        default => 'status-pending',
    };
@endphp
<div class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Patient</th><th>Phone</th><th>Language</th><th>Status</th><th>AI Analysis</th><th></th></tr></thead>
        <tbody>
        @forelse($consultations as $consultation)
            @php($aiStatus = $consultation->latestAiResult?->status)
            <tr>
                <td>{{ $consultation->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $consultation->full_name }}</td>
                <td>{{ $consultation->phone }}</td>
                <td>{{ strtoupper($consultation->preferred_language) }}</td>
                <td><span class="status status-pending">{{ ucfirst($consultation->status) }}</span></td>
                <td>
                    <span class="status {{ $aiBadge($aiStatus) }}">{{ ucfirst($aiStatus ?? 'n/a') }}</span>
                    @if($consultation->latestAiResult?->needs_human_review)
                        <span class="status status-cancelled" style="margin-left:6px;">Human review needed</span>
                    @endif
                </td>
                <td><a class="btn" href="{{ route('admin.consultations.show', $consultation) }}">View</a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="muted">No consultations yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $consultations->links() }}
@endsection
