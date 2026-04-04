@extends('public.layouts.app')

@section('title', __('consultation.recommender_title'))

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">AI Recommender</p>
        <h1 class="section-title">{{ __('consultation.recommender_title') }}</h1>
        <p class="muted">Receive a premium personalized recommendation based on your skin profile and goals.</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    <div class="card" style="max-width:920px;margin:auto;">
        <form method="POST" action="{{ route('recommender.recommend') }}" class="grid grid-2" data-submit-once>
            @csrf
            <div class="form-field"><label>{{ __('consultation.preferred_language') }}</label><select name="preferred_language"><option value="fr" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'fr')>FR</option><option value="en" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'en')>EN</option></select>@error('preferred_language')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.skin_type') }}</label><input name="skin_type" value="{{ old('skin_type', $submitted['skin_type'] ?? '') }}"></div>
            <div class="form-field"><label>{{ __('consultation.skin_sensitivity_level') }}</label><input name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level', $submitted['skin_sensitivity_level'] ?? '') }}"></div>
            <div class="form-field"><label>{{ __('consultation.preferred_goals') }}</label><input name="preferred_goals" value="{{ old('preferred_goals', $submitted['preferred_goals'] ?? '') }}"></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.main_concerns') }}</label><textarea name="main_concerns" required>{{ old('main_concerns', $submitted['main_concerns'] ?? '') }}</textarea>@error('main_concerns')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-span-full btn-row"><button class="btn">{{ __('consultation.get_recommendations') }}</button></div>
        </form>
        <p class="muted" style="margin-top:1rem;">{{ __('consultation.disclaimer') }}</p>
    </div>

    @if($result)
        <div class="card" style="max-width:920px;margin:1.2rem auto 0;">
            <h2>{{ __('consultation.recommended_title') }}</h2>
            @if(($result['status'] ?? '') === 'success')
                <p>{{ $result['explanation_summary'] ?? '' }}</p>
                <div class="card" style="background:var(--secondary);margin-bottom:1rem;">
                    <ul style="margin:0;padding-left:1rem;">
                        @forelse(($recommendedServices ?? []) as $service)
                            <li>{{ $service->localized_name }}</li>
                        @empty
                            <li>{{ __('consultation.no_service_match') }}</li>
                        @endforelse
                    </ul>
                </div>
                <p><strong>{{ __('consultation.caution_notes') }}:</strong> {{ $result['caution_notes'] ?? '—' }}</p>
                <p><strong>{{ __('consultation.next_step') }}:</strong> {{ $result['suggested_next_step'] ?? '—' }}</p>
            @else
                <div class="empty-state">{{ __('consultation.ai_unavailable') }}</div>
            @endif
            <div class="btn-row" style="margin-top:1rem;"><a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a></div>
        </div>
    @endif
</section>
<script>
document.querySelectorAll('form[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (button) { button.disabled = true; button.textContent = 'Loading...'; }
    }, { once: true });
});
</script>
@endsection
