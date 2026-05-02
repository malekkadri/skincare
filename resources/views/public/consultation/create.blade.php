@extends('public.layouts.app')

@section('title', __('consultation.title'))

@section('content')
@include('public.partials.page-hero', ['hero'=>null, 'fallbackTitle'=>__('public.nav.consultation')])

<section class="page-section">
    <div class="page-hero reveal consultation-hero">
        <p class="section-kicker">Consultation</p>
        <h1 class="section-title">{{ __('consultation.title') }}</h1>
        <p class="muted">{{ __('consultation.subtitle') }}</p>
    </div>
</section>

<section class="page-section section-tight-top">
    <div class="card consultation-shell centered-card reveal" style="--card-max:980px;">
        <form method="POST" action="{{ route('consultation.store') }}" class="grid grid-2" data-submit-once enctype="multipart/form-data">
            @csrf
            <div class="form-field"><label for="first_name">{{ __('consultation.first_name') }}</label><input id="first_name" name="first_name" value="{{ old('first_name') }}" required></div>
            <div class="form-field"><label for="last_name">{{ __('consultation.last_name') }}</label><input id="last_name" name="last_name" value="{{ old('last_name') }}"></div>
            <div class="form-field"><label for="phone">{{ __('consultation.phone') }}</label><input id="phone" name="phone" value="{{ old('phone') }}" required></div>
            <div class="form-field"><label for="email">{{ __('consultation.email') }}</label><input id="email" type="email" name="email" value="{{ old('email') }}"></div>
            <div class="form-field"><label for="preferred_language">{{ __('consultation.preferred_language') }}</label><select id="preferred_language" name="preferred_language"><option value="fr" @selected(old('preferred_language', app()->getLocale()) === 'fr')>FR</option><option value="en" @selected(old('preferred_language', app()->getLocale()) === 'en')>EN</option></select></div>
            <div class="form-field"><label for="age_range">{{ __('consultation.age_range') }}</label><input id="age_range" name="age_range" value="{{ old('age_range') }}"></div>
            <div class="form-field"><label for="skin_type">{{ __('consultation.skin_type') }}</label><input id="skin_type" name="skin_type" value="{{ old('skin_type') }}"></div>
            <div class="form-field"><label for="skin_sensitivity_level">{{ __('consultation.skin_sensitivity_level') }}</label><input id="skin_sensitivity_level" name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level') }}"></div>
            <div class="form-field form-span-full"><label for="main_concerns">{{ __('consultation.main_concerns') }}</label><textarea id="main_concerns" name="main_concerns" required>{{ old('main_concerns') }}</textarea></div>
            <div class="form-field"><label for="allergies">{{ __('consultation.allergies') }}</label><textarea id="allergies" name="allergies">{{ old('allergies') }}</textarea></div>
            <div class="form-field"><label for="current_products">{{ __('consultation.current_products') }}</label><textarea id="current_products" name="current_products">{{ old('current_products') }}</textarea></div>
            <div class="form-field"><label for="current_treatments_or_medications">{{ __('consultation.current_treatments_or_medications') }}</label><textarea id="current_treatments_or_medications" name="current_treatments_or_medications">{{ old('current_treatments_or_medications') }}</textarea></div>
            <div class="form-field"><label for="pregnancy_or_breastfeeding_status">{{ __('consultation.pregnancy_or_breastfeeding_status') }}</label><input id="pregnancy_or_breastfeeding_status" name="pregnancy_or_breastfeeding_status" value="{{ old('pregnancy_or_breastfeeding_status') }}"></div>
            <div class="form-field form-span-full"><label for="preferred_goals">{{ __('consultation.preferred_goals') }}</label><textarea id="preferred_goals" name="preferred_goals">{{ old('preferred_goals') }}</textarea></div>
            <div class="form-field form-span-full"><label for="additional_notes">{{ __('consultation.additional_notes') }}</label><textarea id="additional_notes" name="additional_notes">{{ old('additional_notes') }}</textarea></div>

            <div class="form-field form-span-full">
                <label for="consultation_face_images">Face photos (optional, up to 3)</label>
                <input id="consultation_face_images" type="file" name="face_images[]" accept="image/png,image/jpeg,image/webp" multiple>
                <small class="field-help">Use natural light, no filters, and front/left/right angles for better AI quality.</small>
                @error('face_images')<div class="error">{{ $message }}</div>@enderror
                @error('face_images.*')<div class="error">{{ $message }}</div>@enderror
                <div id="consultation-image-preview" class="preview-grid"></div>
            </div>

            <div class="form-span-full consent-wrap soft-panel">
                <label class="consent-check">
                    <input type="checkbox" name="consent" value="1" required class="form-checkbox">
                    <span>{{ __('consultation.consent') }}</span>
                </label>
            </div>

            <div class="form-span-full submit-wrap">
                <button class="btn" type="submit">{{ __('consultation.submit') }}</button>
            </div>
        </form>
    </div>
</section>

<script>
const consultationInput = document.getElementById('consultation_face_images');
const consultationPreview = document.getElementById('consultation-image-preview');
consultationInput?.addEventListener('change', () => {
    consultationPreview.innerHTML = '';
    Array.from(consultationInput.files || []).slice(0,3).forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'soft-panel';
        item.textContent = `${index + 1}. ${file.name}`;
        consultationPreview.appendChild(item);
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
