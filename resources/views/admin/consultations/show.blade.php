@extends('admin.layouts.app')
@section('title', 'Consultation')
@section('header', 'Consultation #'.$consultation->id)
@section('content')
@php
    $statusClass = fn (?string $status) => match($status) {
        'completed' => 'status-confirmed',
        'failed' => 'status-cancelled',
        'processing' => 'status-pending',
        'pending' => 'status-pending',
        'skipped' => 'status-pending',
        default => 'status-pending',
    };
@endphp
<div class="card">
    <h2>Patient</h2>
    <p><strong>{{ $consultation->full_name }}</strong> · {{ $consultation->phone }} · {{ $consultation->email }}</p>
    <p class="muted">Language: {{ strtoupper($consultation->preferred_language) }}</p>
</div>

<div class="card">
    <h2>Face Images</h2>
    @if($consultation->images->isEmpty())
        <p class="muted">No uploaded images.</p>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            @foreach($consultation->images as $image)
                <a href="{{ route('admin.consultations.image', $image) }}" target="_blank" rel="noopener">
                    <img src="{{ route('admin.consultations.image', $image) }}" alt="Consultation face image {{ $loop->iteration }}" style="width:100%;height:180px;object-fit:cover;border-radius:12px;">
                </a>
            @endforeach
        </div>
    @endif
</div>

<div class="card">
    <h2>Latest AI Result</h2>
    @php($ai = $consultation->aiResults->first())
    @if($ai)
        <p><strong>Status:</strong> <span class="status {{ $statusClass($ai->status) }}">{{ ucfirst($ai->status) }}</span></p>
        <p><strong>Provider/Model:</strong> {{ $ai->provider ?: '—' }} / {{ $ai->model ?: '—' }}</p>
        <p><strong>Generated at:</strong> {{ optional($ai->generated_at)->format('Y-m-d H:i') ?: '—' }}</p>
        <p><strong>Processed at:</strong> {{ optional($ai->processed_at)->format('Y-m-d H:i') ?: '—' }}</p>
        <p><strong>User summary:</strong> {{ $ai->user_summary ?: $ai->summary_text ?: '—' }}</p>
        <p><strong>Admin summary:</strong> {{ $ai->admin_summary ?: '—' }}</p>
        <p><strong>Confidence score:</strong> {{ isset($ai->confidence_score) ? number_format($ai->confidence_score * 100, 0).'%' : '—' }}</p>

        <div style="display:flex;gap:8px;flex-wrap:wrap;margin:8px 0 14px;">
            <span class="status {{ $ai->needs_human_review ? 'status-cancelled' : 'status-confirmed' }}">{{ $ai->needs_human_review ? 'Needs Human Review' : 'No Human Review Flag' }}</span>
            <span class="status {{ $ai->refer_to_dermatologist ? 'status-cancelled' : 'status-pending' }}">{{ $ai->refer_to_dermatologist ? 'Refer to Dermatologist' : 'No Dermatology Referral Flag' }}</span>
        </div>

        <h4>Visible concerns</h4>
        <ul>
            @forelse(($ai->normalized_result_json['visible_concerns'] ?? []) as $concern)
                <li>{{ $concern['key'] ?? 'uncertain' }} ({{ number_format(($concern['confidence'] ?? 0) * 100, 0) }}%) - {{ $concern['severity'] ?? 'uncertain' }}</li>
            @empty
                <li>—</li>
            @endforelse
        </ul>

        <h4>Recommended services</h4>
        <ul>
            @forelse(($ai->recommended_services_json ?? []) as $service)
                <li>#{{ $service['service_id'] ?? '?' }} / {{ $service['slug'] ?? '' }} - {{ $service['reason'] ?? '' }}</li>
            @empty
                <li>—</li>
            @endforelse
        </ul>

        <p><strong>Caution flags:</strong> {{ implode(', ', $ai->risk_flags_json ?? []) ?: '—' }}</p>

        @if($ai->error_message)
            <p class="error"><strong>Failure details:</strong> {{ $ai->error_message }}</p>
        @endif

        @if($consultation->images->isNotEmpty() && ! in_array($ai->status, ['pending', 'processing'], true))
            <form method="POST" action="{{ route('admin.consultations.retry-analysis', $consultation) }}">
                @csrf
                <button class="btn" type="submit">Retry AI Analysis</button>
            </form>
        @endif
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
