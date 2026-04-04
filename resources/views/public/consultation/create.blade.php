@extends('public.layouts.app')

@section('title', __('consultation.title'))

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Consultation</p>
        <h1 class="section-title">{{ __('consultation.title') }}</h1>
        <p class="muted">{{ __('consultation.subtitle') }}</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    <div class="card" style="max-width:980px;margin:auto;">
        <form method="POST" action="{{ route('consultation.store') }}" class="grid grid-2" data-submit-once>
            @csrf
            <div class="form-field"><label>{{ __('consultation.first_name') }}</label><input name="first_name" value="{{ old('first_name') }}" required>@error('first_name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.last_name') }}</label><input name="last_name" value="{{ old('last_name') }}">@error('last_name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.phone') }}</label><input name="phone" value="{{ old('phone') }}" required>@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.email') }}</label><input type="email" name="email" value="{{ old('email') }}">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.preferred_language') }}</label><select name="preferred_language"><option value="fr" @selected(old('preferred_language', app()->getLocale()) === 'fr')>FR</option><option value="en" @selected(old('preferred_language', app()->getLocale()) === 'en')>EN</option></select>@error('preferred_language')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.age_range') }}</label><input name="age_range" value="{{ old('age_range') }}"></div>
            <div class="form-field"><label>{{ __('consultation.skin_type') }}</label><input name="skin_type" value="{{ old('skin_type') }}"></div>
            <div class="form-field"><label>{{ __('consultation.skin_sensitivity_level') }}</label><input name="skin_sensitivity_level" value="{{ old('skin_sensitivity_level') }}"></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.main_concerns') }}</label><textarea name="main_concerns" required>{{ old('main_concerns') }}</textarea>@error('main_concerns')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-field"><label>{{ __('consultation.allergies') }}</label><textarea name="allergies">{{ old('allergies') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.current_products') }}</label><textarea name="current_products">{{ old('current_products') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.current_treatments_or_medications') }}</label><textarea name="current_treatments_or_medications">{{ old('current_treatments_or_medications') }}</textarea></div>
            <div class="form-field"><label>{{ __('consultation.pregnancy_or_breastfeeding_status') }}</label><input name="pregnancy_or_breastfeeding_status" value="{{ old('pregnancy_or_breastfeeding_status') }}"></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.preferred_goals') }}</label><textarea name="preferred_goals">{{ old('preferred_goals') }}</textarea></div>
            <div class="form-field form-span-full"><label>{{ __('consultation.additional_notes') }}</label><textarea name="additional_notes">{{ old('additional_notes') }}</textarea></div>
            <div class="form-span-full">
                <label style="display:flex;gap:.5rem;align-items:flex-start;">
                    <input type="checkbox" name="consent" value="1" required style="width:auto;margin-top:.35rem;">
                    <span>{{ __('consultation.consent') }}</span>
                </label>
                @error('consent')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-span-full btn-row"><button class="btn" type="submit">{{ __('consultation.submit') }}</button></div>
        </form>
        <p class="muted" style="margin-top:1rem;">{{ __('consultation.disclaimer') }}</p>
    </div>
</section>
<script>
document.querySelectorAll('form[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (button) { button.disabled = true; button.textContent = @json(__('public.common.submitting')); }
    }, { once: true });
});
</script>
@endsection
