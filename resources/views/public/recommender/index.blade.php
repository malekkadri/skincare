@extends('public.layouts.app')

@section('title', __('consultation.recommender_title'))

@section('content')
<section class="page-section">
    <div class="page-hero reveal recommender-hero">
        <p class="section-kicker">AI Recommender</p>
        <h1 class="section-title">{{ __('consultation.recommender_title') }}</h1>
        <p class="muted">Receive a refined recommendation guided by your skin profile, sensitivity, and goals.</p>

        <div class="recommender-hero__meta" aria-label="Consultation highlights">
            <span>Tailored in moments</span>
            <span>Luxury-care aligned</span>
            <span>No pressure to book</span>
        </div>
    </div>
</section>

<section class="page-section section-tight-top">
    <div class="card recommender-shell centered-card reveal">
        <div class="recommender-shell__intro">
            <h2>Your skin snapshot</h2>
            <p class="muted">Share a few details to receive thoughtful next-step guidance and suggested services.</p>
        </div>

        <form method="POST" action="{{ route('recommender.recommend') }}" class="grid grid-2" data-submit-once>
            @csrf

            <div class="form-field">
                <label for="preferred_language">{{ __('consultation.preferred_language') }}</label>
                <select id="preferred_language" name="preferred_language" aria-invalid="{{ $errors->has('preferred_language') ? 'true' : 'false' }}" aria-describedby="@error('preferred_language') reco_preferred_language_error @enderror">
                    <option value="fr" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'fr')>FR</option>
                    <option value="en" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'en')>EN</option>
                </select>
                @error('preferred_language')<div id="reco_preferred_language_error" class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-field">
                <label for="skin_type">{{ __('consultation.skin_type') }}</label>
                <input id="skin_type" name="skin_type" value="{{ old('skin_type', $submitted['skin_type'] ?? '') }}" aria-describedby="reco-skin-type-help">
                <small id="reco-skin-type-help" class="field-help">Optional. For example: dry, combination, oily, balanced.</small>
            </div>

            <div class="form-field">
                <label for="skin_sensitivity_level">{{ __('consultation.skin_sensitivity_level') }}</label>
                <input id="skin_sensitivity_level" name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level', $submitted['skin_sensitivity_level'] ?? '') }}" aria-describedby="reco-sensitivity-help">
                <small id="reco-sensitivity-help" class="field-help">Optional. Let us know if your skin reacts easily.</small>
            </div>

            <div class="form-field">
                <label for="preferred_goals">{{ __('consultation.preferred_goals') }}</label>
                <input id="preferred_goals" name="preferred_goals" value="{{ old('preferred_goals', $submitted['preferred_goals'] ?? '') }}" aria-describedby="reco-goals-help">
                <small id="reco-goals-help" class="field-help">Optional. Example: hydration, glow, texture, acne support.</small>
            </div>

            <div class="form-field form-span-full">
                <label for="main_concerns">{{ __('consultation.main_concerns') }}</label>
                <textarea id="main_concerns" name="main_concerns" required aria-invalid="{{ $errors->has('main_concerns') ? 'true' : 'false' }}" aria-describedby="reco-concerns-help @error('main_concerns') reco_main_concerns_error @enderror">{{ old('main_concerns', $submitted['main_concerns'] ?? '') }}</textarea>
                <small id="reco-concerns-help" class="field-help">Required. Add your top concerns so suggestions are more relevant.</small>
                @error('main_concerns')<div id="reco_main_concerns_error" class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-span-full recommender-submit-row">
                <button class="btn" type="submit">{{ __('consultation.get_recommendations') }}</button>
                <p class="form-note">You can still book manually any time if you prefer.</p>
            </div>
        </form>

        <div class="recommender-disclaimer">
            <p class="muted">{{ __('consultation.disclaimer') }}</p>
        </div>
    </div>

    @if($result)
        <div class="card recommender-result centered-card reveal">
            <div class="recommender-result__header">
                <p class="section-kicker">Recommendation</p>
                <h2>{{ __('consultation.recommended_title') }}</h2>
            </div>

            @if(($result['status'] ?? '') === 'success')
                <div class="result-summary soft-panel">
                    <h3>Summary</h3>
                    <p>{{ $result['explanation_summary'] ?? '' }}</p>
                </div>

                <div class="result-services soft-panel">
                    <h3>{{ __('consultation.recommended_services') }}</h3>
                    <ul>
                        @forelse(($recommendedServices ?? []) as $service)
                            <li>{{ $service->localized_name }}</li>
                        @empty
                            <li>{{ __('consultation.no_service_match') }}</li>
                        @endforelse
                    </ul>
                </div>

                <div class="result-meta">
                    <div class="soft-panel">
                        <h4>{{ __('consultation.caution_notes') }}</h4>
                        <p>{{ $result['caution_notes'] ?? '—' }}</p>
                    </div>
                    <div class="soft-panel">
                        <h4>{{ __('consultation.next_step') }}</h4>
                        <p>{{ $result['suggested_next_step'] ?? '—' }}</p>
                    </div>
                </div>
            @else
                <div class="empty-state">{{ __('consultation.ai_unavailable') }}</div>
            @endif

            <div class="btn-row result-actions">
                <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
            </div>
        </div>
    @endif
</section>

<style>
    .recommender-hero__meta { display: flex; flex-wrap: wrap; gap: .55rem; margin-top: 1rem; }
    .recommender-hero__meta span {
        border: 1px solid var(--border-strong);
        background: rgba(255,255,255,.65);
        color: var(--text-secondary);
        border-radius: 999px;
        font-size: .78rem;
        letter-spacing: .04em;
        padding: .35rem .7rem;
        text-transform: uppercase;
    }
    .recommender-shell { display: grid; gap: 1.1rem; }
    .recommender-shell__intro h2,
    .recommender-result__header h2 { margin-bottom: .35rem; font-size: clamp(1.3rem, 2.4vw, 1.8rem); }
    .recommender-submit-row { display: grid; gap: .6rem; }
    .recommender-disclaimer {
        border-top: 1px solid var(--border);
        margin-top: .25rem;
        padding-top: .95rem;
    }
    .recommender-result { display: grid; gap: 1rem; margin-top: 1.2rem; }
    .result-actions { margin-top: 1rem; }
    .result-summary,
    .result-services,
    .result-meta > div {
        padding: .95rem 1rem;
    }
    .result-summary h3,
    .result-services h3,
    .result-meta h4 { margin-bottom: .45rem; font-size: 1.02rem; }
    .result-services ul { margin: 0; padding-left: 1rem; display: grid; gap: .35rem; }
    .result-meta { display: grid; gap: .85rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }

    @media (max-width: 900px) {
        .result-meta { grid-template-columns: 1fr; }
    }
</style>

<script>
document.querySelectorAll('form[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (button) { button.disabled = true; button.textContent = @json(__('public.common.submitting')); }
    }, { once: true });
});
</script>
@endsection
