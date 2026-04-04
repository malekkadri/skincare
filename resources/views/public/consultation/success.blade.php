@extends('public.layouts.app')
@section('title', __('consultation.success_title'))
@section('content')
<section class="section">
    <div class="card" style="max-width:780px;margin:auto;">
        <h1>{{ __('consultation.success_title') }}</h1>
        <p>{{ __('consultation.success_message') }}</p>

        @if(($consultation->latestAiResult?->status ?? '') === 'success')
            <h3>{{ __('consultation.recommended_title') }}</h3>
            <p>{{ $consultation->latestAiResult->summary_text }}</p>
            <p><strong>{{ __('consultation.recommended_services') }}:</strong> {{ implode(', ', $consultation->latestAiResult->recommended_services_json ?? []) }}</p>
        @endif

        <div class="menu">
            <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
            <a class="btn btn-alt" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number ?? '') }}" target="_blank">{{ __('consultation.whatsapp_cta') }}</a>
        </div>
    </div>
</section>
@endsection
