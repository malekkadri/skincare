@extends('public.layouts.app')

@section('title', __('consultation.recommender_title'))

@section('content')
<section class="page-section">
    <div class="page-hero reveal recommender-hero">
        <p class="section-kicker">AI Recommender</p>
        <h1 class="section-title">{{ __('consultation.recommender_title') }}</h1>
        <p class="muted">Receive a refined recommendation guided by your skin profile, sensitivity, goals, and optional face photos.</p>
    </div>
</section>

<section class="page-section section-tight-top">
    <div class="card recommender-shell centered-card reveal">
        <form method="POST" action="{{ route('recommender.recommend') }}" class="grid grid-2" data-submit-once enctype="multipart/form-data">
            @csrf
            <div class="form-field">
                <label for="preferred_language">{{ __('consultation.preferred_language') }}</label>
                <select id="preferred_language" name="preferred_language">
                    <option value="fr" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'fr')>FR</option>
                    <option value="en" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'en')>EN</option>
                </select>
            </div>
            <div class="form-field"><label for="skin_type">{{ __('consultation.skin_type') }}</label><input id="skin_type" name="skin_type" value="{{ old('skin_type', $submitted['skin_type'] ?? '') }}"></div>
            <div class="form-field"><label for="skin_sensitivity_level">{{ __('consultation.skin_sensitivity_level') }}</label><input id="skin_sensitivity_level" name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level', $submitted['skin_sensitivity_level'] ?? '') }}"></div>
            <div class="form-field"><label for="preferred_goals">{{ __('consultation.preferred_goals') }}</label><input id="preferred_goals" name="preferred_goals" value="{{ old('preferred_goals', $submitted['preferred_goals'] ?? '') }}"></div>
            <div class="form-field form-span-full">
                <label for="main_concerns">{{ __('consultation.main_concerns') }}</label>
                <textarea id="main_concerns" name="main_concerns" required>{{ old('main_concerns', $submitted['main_concerns'] ?? '') }}</textarea>
            </div>

            <div class="form-field form-span-full">
                <label for="face_images">Face photos (optional, up to 3)</label>
                <input id="face_images" type="file" name="face_images[]" accept="image/png,image/jpeg,image/webp" multiple>
                <small class="field-help">For best results upload front, left, and right face photos in clear lighting, no filters, and close framing.</small>
                @error('face_images')<div class="error">{{ $message }}</div>@enderror
                @error('face_images.*')<div class="error">{{ $message }}</div>@enderror
                <div id="face-image-preview" class="preview-grid"></div>
            </div>

            <div class="form-span-full recommender-submit-row">
                <button class="btn" type="submit">{{ __('consultation.get_recommendations') }}</button>
            </div>
        </form>

        @if($result)
            <div class="card recommender-result centered-card reveal">
                @if(($result['status'] ?? '') === 'success')
                    <div class="soft-panel">
                        <h3>Skin summary</h3>
                        <p>{{ $result['explanation_summary'] ?? '—' }}</p>
                    </div>

                    @if(! empty($result['normalized_result']['visible_concerns']))
                        <div class="soft-panel">
                            <h3>Visible concerns</h3>
                            <ul>
                                @foreach($result['normalized_result']['visible_concerns'] as $concern)
                                    <li>{{ $concern['key'] }} ({{ number_format(($concern['confidence'] ?? 0) * 100, 0) }}%) - {{ $concern['severity'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="soft-panel">
                        <h3>{{ __('consultation.recommended_services') }}</h3>
                        <ul>
                            @forelse(($recommendedServices ?? []) as $service)
                                <li>{{ $service->localized_name }}</li>
                            @empty
                                <li>{{ __('consultation.no_service_match') }}</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="soft-panel">
                        <h4>Confidence</h4>
                        <p>{{ isset($result['normalized_result']['confidence_score']) ? number_format($result['normalized_result']['confidence_score'] * 100, 0).'%' : '—' }}</p>
                        <h4>{{ __('consultation.caution_notes') }}</h4>
                        <p>{{ $result['caution_notes'] ?: '—' }}</p>
                    </div>
                @else
                    <div class="empty-state">{{ __('consultation.ai_unavailable') }}</div>
                @endif

                <div class="btn-row result-actions">
                    <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
const input = document.getElementById('face_images');
const preview = document.getElementById('face-image-preview');
input?.addEventListener('change', () => {
    preview.innerHTML = '';
    Array.from(input.files || []).slice(0,3).forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'soft-panel';
        item.textContent = `${index + 1}. ${file.name}`;
        preview.appendChild(item);
    });
});

document.querySelectorAll('form[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (button) { button.disabled = true; button.textContent = @json(__('public.common.submitting')); }
    }, { once: true });
});
</script>
@endsection
