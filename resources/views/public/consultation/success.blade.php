@extends('public.layouts.app')

@section('title', __('consultation.success_title'))

@section('content')
<section class="page-section">
    <div class="card consultation-success centered-card reveal" style="--card-max:860px;">
        <h1 class="section-title">{{ __('consultation.success_title') }}</h1>
        <p class="muted">{{ __('consultation.success_message') }}</p>

        @php($ai = $consultation->latestAiResult)
        @if($ai && in_array($ai->status, ['pending', 'processing']))
            <div class="soft-panel">Your AI face-image analysis is in progress. Our team can still proceed manually if needed.</div>
        @elseif($ai && $ai->status === 'completed')
            <div class="soft-panel">
                <h2>{{ __('consultation.recommended_title') }}</h2>
                <p>{{ $ai->user_summary ?: $ai->summary_text }}</p>
                <p><strong>{{ __('consultation.recommended_services') }}:</strong>
                    {{ collect($ai->recommended_services_json ?? [])->pluck('slug')->implode(', ') ?: '—' }}
                </p>
                <p><strong>{{ __('consultation.caution_notes') }}:</strong> {{ implode(', ', $ai->risk_flags_json ?? []) ?: '—' }}</p>
            </div>
        @elseif($ai && $ai->status === 'failed')
            <div class="soft-panel">AI analysis is temporarily unavailable. Our team will review your consultation manually.</div>
        @endif

        <div class="success-actions">
            <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
        </div>
    </div>
</section>
@endsection
