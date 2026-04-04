@extends('public.layouts.app')

@section('title', __('consultation.success_title'))

@section('content')
<section class="page-section">
    <div class="card consultation-success centered-card reveal" style="--card-max:860px;">
        <div class="success-header">
            <p class="section-kicker">Consultation</p>
            <h1 class="section-title">{{ __('consultation.success_title') }}</h1>
            <p class="muted">{{ __('consultation.success_message') }}</p>
            <p class="muted">Our team reviews each submission thoughtfully to ensure your next step feels safe, clear, and aligned with your goals.</p>
        </div>

        @if(($consultation->latestAiResult?->status ?? '') === 'success')
            <div class="success-ai-block soft-panel" aria-label="AI recommendation summary">
                <h2>{{ __('consultation.recommended_title') }}</h2>
                <p>{{ $consultation->latestAiResult->summary_text }}</p>

                <div class="success-ai-services">
                    <h3>{{ __('consultation.recommended_services') }}</h3>
                    <p>{{ implode(', ', $consultation->latestAiResult->recommended_services_json ?? []) }}</p>
                </div>
            </div>
        @endif

        <div class="success-actions">
            <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
            <a class="btn btn-soft" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number ?? '') }}" target="_blank" rel="noopener">{{ __('consultation.whatsapp_cta') }}</a>
        </div>

        <div class="success-next-step soft-panel">
            <h3>What happens next?</h3>
            <ul>
                <li>We review your details and recommendations.</li>
                <li>You can book your preferred service immediately.</li>
                <li>Need help deciding? Reach out on WhatsApp for guidance.</li>
            </ul>
        </div>
    </div>
</section>

<style>
    .consultation-success { display: grid; gap: 1rem; }
    .success-header { border-bottom: 1px solid var(--border); padding-bottom: .85rem; }
    .success-ai-block,
    .success-next-step {
        padding: 1rem;
    }
    .success-ai-block h2 { font-size: clamp(1.2rem, 2vw, 1.55rem); margin-bottom: .45rem; }
    .success-ai-services {
        margin-top: .85rem;
        padding-top: .75rem;
        border-top: 1px solid var(--border);
    }
    .success-ai-services h3,
    .success-next-step h3 { margin-bottom: .4rem; font-size: 1.02rem; }
    .success-next-step ul { margin: 0; padding-left: 1rem; color: var(--text-secondary); }
    .success-actions { display: flex; flex-wrap: wrap; gap: .75rem; }
</style>
@endsection
