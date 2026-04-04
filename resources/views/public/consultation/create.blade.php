@extends('public.layouts.app')

@section('title', __('consultation.title'))

@section('content')
<section class="page-section">
    <div class="page-hero reveal consultation-hero">
        <p class="section-kicker">Consultation</p>
        <h1 class="section-title">{{ __('consultation.title') }}</h1>
        <p class="muted">{{ __('consultation.subtitle') }}</p>
        <p class="muted">Complete the form below and we will review your profile with care before recommending your next best step.</p>
    </div>
</section>

<section class="page-section section-tight-top">
    <div class="card consultation-shell centered-card reveal" style="--card-max:980px;">
        <div class="form-progress" aria-hidden="true">
            <span class="is-active">1. Profile</span>
            <span class="is-active">2. Skin details</span>
            <span class="is-active">3. Submit</span>
        </div>

        <form method="POST" action="{{ route('consultation.store') }}" class="grid grid-2" data-submit-once>
            @csrf

            <div class="form-section form-span-full">
                <h2>Your details</h2>
                <p class="muted">Basic contact details so we can follow up with recommendations and next steps.</p>
            </div>
            <div class="form-field"><label>{{ __('consultation.first_name') }}</label><input name="first_name" value="{{ old('first_name') }}" required>@error('first_name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.last_name') }}</label><input name="last_name" value="{{ old('last_name') }}" aria-describedby="last-name-help"><small id="last-name-help" class="field-help">Optional.</small>@error('last_name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.phone') }}</label><input name="phone" value="{{ old('phone') }}" required>@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.email') }}</label><input type="email" name="email" value="{{ old('email') }}" aria-describedby="email-help"><small id="email-help" class="field-help">Optional, but helpful for follow-up.</small>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.preferred_language') }}</label><select name="preferred_language"><option value="fr" @selected(old('preferred_language', app()->getLocale()) === 'fr')>FR</option><option value="en" @selected(old('preferred_language', app()->getLocale()) === 'en')>EN</option></select>@error('preferred_language')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.age_range') }}</label><input name="age_range" value="{{ old('age_range') }}" aria-describedby="age-help"><small id="age-help" class="field-help">Optional.</small></div>

            <div class="form-section form-span-full">
                <h2>Skin profile</h2>
                <p class="muted">Tell us about your current skin condition so recommendations feel precise and safe.</p>
            </div>
            <div class="form-field"><label>{{ __('consultation.skin_type') }}</label><input name="skin_type" value="{{ old('skin_type') }}"></div>
            <div class="form-field"><label>{{ __('consultation.skin_sensitivity_level') }}</label><input name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level') }}"></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.main_concerns') }}</label><textarea name="main_concerns" required aria-describedby="concerns-help">{{ old('main_concerns') }}</textarea><small id="concerns-help" class="field-help">Required. Share your top concerns, what you notice, and when they appear.</small>@error('main_concerns')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.allergies') }}</label><textarea name="allergies">{{ old('allergies') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.current_products') }}</label><textarea name="current_products">{{ old('current_products') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.current_treatments_or_medications') }}</label><textarea name="current_treatments_or_medications">{{ old('current_treatments_or_medications') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.pregnancy_or_breastfeeding_status') }}</label><input name="pregnancy_or_breastfeeding_status" value="{{ old('pregnancy_or_breastfeeding_status') }}"></div>

            <div class="form-section form-span-full">
                <h2>Goals & preferences</h2>
                <p class="muted">Optional details that help tailor your treatment path and home-care strategy.</p>
            </div>
            <div class="form-field form-span-full"><label>{{ __('consultation.preferred_goals') }}</label><textarea name="preferred_goals">{{ old('preferred_goals') }}</textarea></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.additional_notes') }}</label><textarea name="additional_notes">{{ old('additional_notes') }}</textarea></div>

            <div class="form-span-full consent-wrap soft-panel">
                <label class="consent-check">
                    <input type="checkbox" name="consent" value="1" required class="form-checkbox">
                    <span>{{ __('consultation.consent') }}</span>
                </label>
                <small class="field-help">Your information is used only to prepare your personalized consultation response.</small>
                @error('consent')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-span-full submit-wrap">
                <button class="btn" type="submit">{{ __('consultation.submit') }}</button>
                <p class="form-note">You will be redirected to a confirmation page with your next action options.</p>
            </div>
        </form>

        <div class="consultation-disclaimer">
            <p class="muted">{{ __('consultation.disclaimer') }}</p>
        </div>
    </div>
</section>

<style>
    .consultation-shell { display: grid; gap: 1rem; }
    .form-progress { display: flex; flex-wrap: wrap; gap: .5rem; }
    .form-progress span {
        padding: .35rem .68rem;
        border-radius: 999px;
        font-size: .75rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        border: 1px solid var(--border-strong);
        background: rgba(255,255,255,.72);
        color: var(--text-secondary);
    }
    .form-progress span.is-active { color: var(--text-primary); }
    .form-section {
        margin-top: .2rem;
        border-top: 1px solid var(--border);
        padding-top: .9rem;
    }
    .form-section h2 { margin-bottom: .3rem; font-size: clamp(1.2rem, 2vw, 1.55rem); }
    .consent-wrap { display: grid; gap: .45rem; }
    .consent-check {
        display: flex;
        gap: .5rem;
        align-items: flex-start;
        margin: 0;
    }
    .submit-wrap { display: grid; gap: .6rem; }
    .consultation-disclaimer {
        border-top: 1px solid var(--border);
        padding-top: .95rem;
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
