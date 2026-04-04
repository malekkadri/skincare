@extends('admin.layouts.app')
@section('title', 'Consultations')
@section('header', 'Consultations')
@section('content')
<div class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Client</th><th>Phone</th><th>Language</th><th>Status</th><th>AI Summary</th><th></th></tr></thead>
        <tbody>
        @forelse($consultations as $consultation)
            <tr>
                <td>{{ $consultation->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $consultation->full_name }}</td>
                <td>{{ $consultation->phone }}</td>
                <td>{{ strtoupper($consultation->preferred_language) }}</td>
                <td><span class="status status-pending">{{ ucfirst($consultation->status) }}</span></td>
                <td>{{ $consultation->latestAiResult?->status ?? 'n/a' }}</td>
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
