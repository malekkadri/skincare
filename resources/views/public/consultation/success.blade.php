@extends('public.layouts.app')

@section('title', __('consultation.success_title'))

@section('content')
<section class="page-section">
    <div class="card" style="max-width:860px;margin:auto;">
        <p class="section-kicker">Consultation</p>
        <h1 class="section-title">{{ __('consultation.success_title') }}</h1>
        <p class="muted">{{ __('consultation.success_message') }}</p>

        @if(($consultation->latestAiResult?->status ?? '') === 'success')
            <div class="card" style="background:var(--secondary);margin-top:1rem;">
                <h3 style="margin-bottom:.5rem;">{{ __('consultation.recommended_title') }}</h3>
                <p>{{ $consultation->latestAiResult->summary_text }}</p>
                <p><strong>{{ __('consultation.recommended_services') }}:</strong> {{ implode(', ', $consultation->latestAiResult->recommended_services_json ?? []) }}</p>
            </div>
        @endif

        <div class="btn-row" style="margin-top:1rem;">
            <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
            <a class="btn btn-soft" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number ?? '') }}" target="_blank">{{ __('consultation.whatsapp_cta') }}</a>
        </div>
    </div>
</section>
@endsection
