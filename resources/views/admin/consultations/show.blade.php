@extends('admin.layouts.app')
@section('title', 'Consultation')
@section('header', 'Consultation #'.$consultation->id)
@section('content')
<div class="card">
    <h2>Client</h2>
    <p><strong>{{ $consultation->full_name }}</strong> · {{ $consultation->phone }} · {{ $consultation->email }}</p>
    <p class="muted">Language: {{ strtoupper($consultation->preferred_language) }}</p>
</div>

<div class="card">
    <h2>Consultation Answers</h2>
    <p><strong>Main concerns:</strong> {{ $consultation->main_concerns }}</p>
    <p><strong>Skin type:</strong> {{ $consultation->skin_type ?: '—' }}</p>
    <p><strong>Sensitivity:</strong> {{ $consultation->skin_sensitivity_level ?: '—' }}</p>
    <p><strong>Allergies:</strong> {{ $consultation->allergies ?: '—' }}</p>
    <p><strong>Goals:</strong> {{ $consultation->preferred_goals ?: '—' }}</p>
    <p><strong>Notes:</strong> {{ $consultation->additional_notes ?: '—' }}</p>
</div>

<div class="card">
    <h2>Latest AI Result</h2>
    @php($ai = $consultation->aiResults->first())
    @if($ai)
        <p><strong>Status:</strong> {{ $ai->status }}</p>
        <p><strong>Summary:</strong> {{ $ai->summary_text ?: '—' }}</p>
        <p><strong>Recommended services:</strong> {{ implode(', ', $ai->recommended_services_json ?? []) ?: '—' }}</p>
        <p><strong>Risk flags:</strong> {{ implode(', ', $ai->risk_flags_json ?? []) ?: '—' }}</p>
        @if($ai->error_message)<p class="error">{{ $ai->error_message }}</p>@endif
    @else
        <p class="muted">No AI result yet.</p>
    @endif
</div>

<form method="POST" action="{{ route('admin.consultations.update', $consultation) }}" class="card">@csrf @method('PUT')
    <h2>Admin Review</h2>
    <div class="grid">
        <div>
            <label>Status</label>
            <select name="status">
                @foreach(\App\Models\Consultation::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $consultation->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Admin notes</label>
            <textarea name="admin_notes">{{ old('admin_notes', $consultation->admin_notes) }}</textarea>
        </div>
    </div>
    <button class="btn">Save Review</button>
</form>
@endsection
