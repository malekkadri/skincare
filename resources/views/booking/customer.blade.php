@extends('booking.layouts.app')

@section('content')
<div class="card booking-step-customer" data-step-customer>
    <div class="step-intro customer-step-intro">
        <p class="step-kicker">{{ __('booking.step_customer') }}</p>
        <h1 class="title step-title">{{ __('booking.customer_heading') }}</h1>
        <p class="subtitle step-subtitle">{{ __('booking.customer_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('booking.customer.save') }}" class="customer-form" novalidate>
        @csrf

        <section class="customer-section" aria-labelledby="contact-details-title">
            <div class="customer-section-head">
                <h2 id="contact-details-title" class="customer-section-title">{{ __('booking.customer_contact_title') }}</h2>
                <p class="customer-section-copy">{{ __('booking.customer_contact_copy') }}</p>
            </div>

            <div class="grid grid-2 customer-grid">
                <div class="customer-field-wrap">
                    <label for="first_name">{{ __('booking.first_name') }}</label>
                    <input
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', $wizard['customer']['first_name'] ?? '') }}"
                        required
                        autocomplete="given-name"
                        @class(['field-input', 'field-input-error' => $errors->has('first_name')])
                        aria-invalid="{{ $errors->has('first_name') ? 'true' : 'false' }}"
                        aria-describedby="first_name_hint @error('first_name') first_name_error @enderror"
                    >
                    <p id="first_name_hint" class="field-hint">{{ __('booking.first_name_hint') }}</p>
                    @error('first_name')
                        <p id="first_name_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="customer-field-wrap">
                    <label for="last_name">{{ __('booking.last_name') }} <span class="optional-pill">{{ __('booking.optional') }}</span></label>
                    <input
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', $wizard['customer']['last_name'] ?? '') }}"
                        autocomplete="family-name"
                        @class(['field-input', 'field-input-error' => $errors->has('last_name')])
                        aria-invalid="{{ $errors->has('last_name') ? 'true' : 'false' }}"
                        aria-describedby="last_name_hint @error('last_name') last_name_error @enderror"
                    >
                    <p id="last_name_hint" class="field-hint">{{ __('booking.last_name_hint') }}</p>
                    @error('last_name')
                        <p id="last_name_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="customer-field-wrap">
                    <label for="phone">{{ __('booking.phone') }}</label>
                    <input
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $wizard['customer']['phone'] ?? '') }}"
                        required
                        autocomplete="tel"
                        @class(['field-input', 'field-input-error' => $errors->has('phone')])
                        aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                        aria-describedby="phone_hint @error('phone') phone_error @enderror"
                    >
                    <p id="phone_hint" class="field-hint">{{ __('booking.phone_hint') }}</p>
                    @error('phone')
                        <p id="phone_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="customer-field-wrap">
                    <label for="email">{{ __('booking.email') }} <span class="optional-pill">{{ __('booking.optional') }}</span></label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $wizard['customer']['email'] ?? '') }}"
                        autocomplete="email"
                        @class(['field-input', 'field-input-error' => $errors->has('email')])
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="email_hint @error('email') email_error @enderror"
                    >
                    <p id="email_hint" class="field-hint">{{ __('booking.email_hint') }}</p>
                    @error('email')
                        <p id="email_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="customer-field-wrap">
                    <label for="preferred_language">{{ __('booking.language') }}</label>
                    <select
                        id="preferred_language"
                        name="preferred_language"
                        @class(['field-input', 'field-input-error' => $errors->has('preferred_language')])
                        aria-invalid="{{ $errors->has('preferred_language') ? 'true' : 'false' }}"
                        aria-describedby="preferred_language_hint @error('preferred_language') preferred_language_error @enderror"
                    >
                        <option value="fr" @selected(old('preferred_language', $wizard['customer']['preferred_language'] ?? app()->getLocale())==='fr')>Français</option>
                        <option value="en" @selected(old('preferred_language', $wizard['customer']['preferred_language'] ?? app()->getLocale())==='en')>English</option>
                    </select>
                    <p id="preferred_language_hint" class="field-hint">{{ __('booking.language_hint') }}</p>
                    @error('preferred_language')
                        <p id="preferred_language_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="customer-field-wrap customer-field-wide">
                    <label for="notes">{{ __('booking.notes') }} <span class="optional-pill">{{ __('booking.optional') }}</span></label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                        @class(['field-input field-textarea', 'field-input-error' => $errors->has('notes')])
                        aria-invalid="{{ $errors->has('notes') ? 'true' : 'false' }}"
                        aria-describedby="notes_hint @error('notes') notes_error @enderror"
                    >{{ old('notes', $wizard['customer']['notes'] ?? '') }}</textarea>
                    <p id="notes_hint" class="field-hint">{{ __('booking.notes_hint') }}</p>
                    @error('notes')
                        <p id="notes_error" class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <div class="customer-footer">
            <p class="customer-footer-note">{{ __('booking.customer_footer_note') }}</p>
            <button class="btn customer-continue" type="submit">{{ __('booking.continue') }}</button>
        </div>
    </form>
</div>

<style>
    .booking-step-customer {
        padding: clamp(1.1rem, 2.4vw, 1.8rem);
        max-width: 840px;
        margin: 0 auto;
    }

    .customer-step-intro {
        margin-bottom: 1.15rem;
    }

    .step-kicker {
        display: inline-flex;
        align-items: center;
        margin: 0 0 .4rem;
        font-size: .74rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #897768;
        font-weight: 600;
    }

    .step-title {
        margin: 0 0 .34rem;
        font-size: clamp(1.35rem, 1.07rem + 1vw, 2rem);
    }

    .step-subtitle {
        margin: 0;
        max-width: 66ch;
    }

    .customer-form {
        display: grid;
        gap: 1rem;
    }

    .customer-section {
        border: 1px solid #e7d9cd;
        border-radius: 22px;
        background: linear-gradient(180deg, #fffdfb 0%, #fcf7f1 100%);
        padding: clamp(1rem, 2.2vw, 1.35rem);
    }

    .customer-section-head {
        margin-bottom: .95rem;
    }

    .customer-section-title {
        margin: 0;
        font-size: clamp(1.08rem, .96rem + .44vw, 1.3rem);
    }

    .customer-section-copy {
        margin: .38rem 0 0;
        color: #726458;
        font-size: .88rem;
        max-width: 62ch;
    }

    .customer-grid {
        gap: .92rem;
    }

    .customer-field-wrap {
        min-width: 0;
    }

    .customer-field-wide {
        grid-column: 1 / -1;
    }

    .field-input {
        border-color: #dfd3c7;
        background: #fffefc;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .field-input:hover {
        border-color: #d8c4af;
    }

    .field-input:focus-visible {
        border-color: #c9a886;
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .15);
    }

    .field-input-error {
        border-color: #c05c5c;
        background: #fffafa;
    }

    .field-input-error:focus-visible {
        border-color: #b84444;
        box-shadow: 0 0 0 3px rgba(185, 68, 68, .15);
    }

    .field-textarea {
        min-height: 132px;
        resize: vertical;
        line-height: 1.5;
    }

    .field-hint {
        margin: .44rem 0 0;
        color: #7a6f65;
        font-size: .8rem;
        line-height: 1.4;
    }

    .field-error {
        margin-top: .3rem;
    }

    .optional-pill {
        display: inline-flex;
        align-items: center;
        margin-left: .3rem;
        padding: .05rem .43rem;
        border-radius: 999px;
        border: 1px solid #e4d3c3;
        color: #857362;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .02em;
        vertical-align: middle;
    }

    .customer-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .8rem;
        border: 1px solid #e8dccf;
        border-radius: 18px;
        background: #fffdfa;
        padding: .82rem .88rem;
    }

    .customer-footer-note {
        margin: 0;
        color: #6e6257;
        font-size: .83rem;
        max-width: 62ch;
    }

    .customer-continue {
        min-width: 148px;
    }

    @media (max-width: 760px) {
        .booking-step-customer {
            padding: 1rem;
        }

        .customer-section {
            border-radius: 18px;
            padding: .95rem;
        }

        .customer-grid {
            gap: .84rem;
        }

        .customer-field-wide {
            grid-column: 1 / -1;
        }

        .customer-footer {
            align-items: stretch;
            padding: .85rem;
        }

        .customer-continue {
            width: 100%;
        }
    }
</style>
@endsection
