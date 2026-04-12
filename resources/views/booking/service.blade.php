@extends('booking.layouts.app')

@section('content')
<div class="card booking-step-service" data-step-service>
    <div class="step-intro">
        <p class="step-kicker">{{ __('booking.step_service') }}</p>
        <h1 class="title step-title">{{ __('booking.service_heading') }}</h1>
        <p class="subtitle step-subtitle">{{ __('booking.service_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('booking.service.save') }}" data-service-form>
        @csrf

        <fieldset class="service-options" aria-describedby="service-help service-selection-error">
            <legend class="sr-only">{{ __('booking.service_legend') }}</legend>

            <p id="service-help" class="service-help-text">{{ __('booking.service_help') }}</p>

            <div class="grid grid-2 service-options-grid">
                @forelse($services as $service)
                    <label class="service-card service-option" for="service-{{ $service->id }}">
                        <input
                            id="service-{{ $service->id }}"
                            class="service-option-input"
                            type="radio"
                            name="service_id"
                            value="{{ $service->id }}"
                            @checked(old('service_id', $wizard['service']?->id) === $service->id)
                        >

                        <span class="service-card-select-indicator" aria-hidden="true"></span>

                        <span class="service-card-main">
                            <span class="service-name">{{ $service->localized_name }}</span>
                            @if(filled($service->localized_short_description))
                                <span class="service-description">{{ $service->localized_short_description }}</span>
                            @endif
                        </span>

                        <span class="service-meta" aria-label="{{ __('booking.service_meta_aria') }}">
                            <span class="service-pill">{{ $service->duration_minutes }} min</span>
                            <span class="service-price">{{ $service->display_price }}</span>
                        </span>
                    </label>
                @empty
                    <div class="service-empty" role="status">
                        <strong>{{ __('booking.service_empty_title') }}</strong>
                        <p>{{ __('booking.service_empty_copy') }}</p>
                    </div>
                @endforelse
            </div>
        </fieldset>

        @error('service_id')
            <p id="service-selection-error" class="field-error">{{ $message }}</p>
        @enderror

        <div class="service-step-footer">
            <p class="service-footer-note">{{ __('booking.service_footer_note') }}</p>
            <button class="btn service-continue" type="submit" @disabled($services->isEmpty())>{{ __('booking.continue') }}</button>
        </div>
    </form>
</div>

<style>
    .booking-step-service {
        padding: clamp(1.1rem, 2.3vw, 1.7rem);
    }

    .step-intro {
        margin-bottom: 1.2rem;
    }

    .step-kicker {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        margin: 0 0 .4rem;
        font-size: .74rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #897768;
        font-weight: 600;
    }

    .step-title {
        margin: 0 0 .35rem;
        font-size: clamp(1.4rem, 1.1rem + 1vw, 2rem);
    }

    .step-subtitle {
        margin-bottom: 0;
        max-width: 62ch;
    }

    .service-options {
        border: 0;
        padding: 0;
        margin: 0;
    }

    .service-help-text {
        margin: 0 0 .95rem;
        padding: .78rem .9rem;
        border-radius: 14px;
        border: 1px solid #ecdfd3;
        background: #fcf8f3;
        color: #705f51;
        font-size: .88rem;
    }

    .service-options-grid {
        gap: .82rem;
    }

    .service-option {
        position: relative;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: start;
        gap: .78rem;
        min-height: 126px;
        padding: .95rem 1rem;
        border: 1px solid #eadccd;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffdfb 0%, #fdfaf6 100%);
        box-shadow: 0 8px 22px rgba(109, 87, 66, 0.04);
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease, background-color .2s ease;
        cursor: pointer;
    }

    .service-option:hover {
        border-color: #ddc8b2;
        box-shadow: 0 10px 24px rgba(109, 87, 66, 0.1);
        transform: translateY(-1px);
    }

    .service-option:focus-within {
        border-color: #d1b599;
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .14), 0 10px 24px rgba(109, 87, 66, 0.1);
    }

    .service-option-input {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        pointer-events: none;
    }

    .service-card-select-indicator {
        position: relative;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 999px;
        border: 1.5px solid #ccb29a;
        background: #fff;
        margin-top: .2rem;
        transition: border-color .2s ease, background-color .2s ease;
    }

    .service-card-select-indicator::after {
        content: '';
        position: absolute;
        inset: 3px;
        border-radius: 999px;
        transform: scale(.35);
        opacity: 0;
        background: linear-gradient(180deg, #c99f75 0%, var(--accent) 100%);
        transition: transform .2s ease, opacity .2s ease;
    }

    .service-option:has(.service-option-input:checked) {
        border-color: #d3b596;
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .14), 0 14px 26px rgba(109, 87, 66, .12);
        background: linear-gradient(180deg, #fffdfa 0%, #f9f2eb 100%);
    }

    .service-option:has(.service-option-input:checked) .service-card-select-indicator {
        border-color: #b7885b;
        background: #fef8f2;
    }

    .service-option:has(.service-option-input:checked) .service-card-select-indicator::after {
        transform: scale(1);
        opacity: 1;
    }

    .service-card-main {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        min-width: 0;
    }

    .service-name {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.12rem;
        line-height: 1.25;
        color: #2f2924;
    }

    .service-description {
        color: #726659;
        font-size: .9rem;
        line-height: 1.45;
    }

    .service-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: .46rem;
    }

    .service-pill {
        padding: .28rem .58rem;
        border-radius: 999px;
        background: #f6eee5;
        border: 1px solid #e4d2c0;
        color: #7c6651;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .service-price {
        font-size: 1rem;
        font-weight: 700;
        color: #8d6340;
        letter-spacing: .01em;
    }

    .service-step-footer {
        margin-top: 1.05rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .9rem;
        flex-wrap: wrap;
    }

    .service-footer-note {
        margin: 0;
        color: #75695d;
        font-size: .86rem;
    }

    .service-continue {
        min-width: 168px;
        min-height: 46px;
        padding-inline: 1.35rem;
    }

    .service-empty {
        border: 1px dashed #dcc9b6;
        border-radius: 16px;
        padding: 1.15rem;
        background: #fcf8f3;
        color: #6d5d4f;
    }

    .service-empty p {
        margin: .4rem 0 0;
        color: #78695c;
    }

    @media (max-width: 760px) {
        .service-option {
            grid-template-columns: auto 1fr;
            gap: .72rem;
            padding: .92rem;
        }

        .service-meta {
            grid-column: 2;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: .12rem;
        }

        .service-step-footer {
            align-items: stretch;
        }

        .service-continue {
            width: 100%;
        }
    }
</style>

<script>
    (() => {
        const form = document.querySelector('[data-service-form]');
        if (!form) return;

        const continueButton = form.querySelector('.service-continue');
        const serviceInputs = form.querySelectorAll('input[name="service_id"]');
        if (!continueButton || serviceInputs.length === 0) return;

        const syncButtonState = () => {
            const hasSelection = form.querySelector('input[name="service_id"]:checked') !== null;
            continueButton.disabled = !hasSelection;
            continueButton.setAttribute('aria-disabled', String(!hasSelection));
        };

        serviceInputs.forEach((input) => {
            input.addEventListener('change', syncButtonState, { passive: true });
        });

        syncButtonState();
    })();
</script>
@endsection
