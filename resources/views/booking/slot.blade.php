@extends('booking.layouts.app')

@section('content')
<div class="card booking-step-slot" data-step-slot>
    <div class="step-intro slot-step-intro">
        <p class="step-kicker">{{ __('booking.step_slot') }}</p>
        <h1 class="title step-title">{{ __('booking.slot_heading') }}</h1>
        <p class="subtitle step-subtitle">{{ __('booking.slot_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('booking.slot.save') }}" data-slot-form>
        @csrf

        <div class="slot-selection-shell">
            <div class="slot-selection-head">
                <p class="slot-date-label">{{ __('booking.slot_date_label') }}</p>
                <p class="slot-date-value">{{ $wizard['appointment_date'] }}</p>
                <p class="slot-reassurance">{{ __('booking.slot_reassurance') }}</p>
            </div>

            <fieldset class="slot-options" aria-describedby="slot-help slot-selection-error">
                <legend class="sr-only">{{ __('booking.slot_legend') }}</legend>
                <p id="slot-help" class="slot-help-text">{{ __('booking.slot_help') }}</p>

                <div class="grid grid-2 slot-options-grid">
                    @forelse($slots as $slot)
                        <label class="slot-option" for="slot-{{ md5($slot) }}">
                            <input
                                id="slot-{{ md5($slot) }}"
                                class="slot-option-input"
                                type="radio"
                                name="start_time"
                                value="{{ $slot }}"
                                @checked(old('start_time', $wizard['start_time'])===$slot)
                            >

                            <span class="slot-option-indicator" aria-hidden="true"></span>

                            <span class="slot-option-main">
                                <strong class="slot-option-time">{{ $slot }}</strong>
                                <span class="slot-option-caption">{{ __('booking.slot_start_time') }}</span>
                            </span>

                            <span class="slot-option-check" aria-hidden="true">{{ __('booking.slot_selected') }}</span>
                        </label>
                    @empty
                        <div class="slot-empty" role="status">
                            <p class="slot-empty-title">{{ __('booking.slot_unavailable') }}</p>
                            <p class="slot-empty-copy">{{ __('booking.slot_empty_copy') }}</p>
                            <a href="{{ route('booking.date') }}" class="btn btn-soft slot-empty-action">{{ __('booking.slot_pick_another_date') }}</a>
                        </div>
                    @endforelse
                </div>
            </fieldset>
        </div>

        @error('start_time')
            <p id="slot-selection-error" class="field-error">{{ $message }}</p>
        @enderror

        <div class="slot-step-footer">
            <p class="slot-footer-note">{{ __('booking.slot_footer_note') }}</p>
            <button
                id="slot_submit"
                class="btn slot-continue"
                type="submit"
                @disabled(count($slots) === 0 || !old('start_time', $wizard['start_time']))
            >
                {{ __('booking.continue') }}
            </button>
        </div>
    </form>
</div>

<style>
    .booking-step-slot {
        padding: clamp(1.1rem, 2.3vw, 1.75rem);
    }

    .slot-step-intro {
        margin-bottom: 1.1rem;
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
        margin: 0 0 .35rem;
        font-size: clamp(1.35rem, 1.1rem + 1vw, 2rem);
    }

    .step-subtitle {
        margin-bottom: 0;
        max-width: 62ch;
    }

    .slot-selection-shell {
        border: 1px solid #e8dacd;
        border-radius: 20px;
        background: linear-gradient(180deg, #fffdfb 0%, #fbf6f0 100%);
        padding: clamp(.95rem, 2.1vw, 1.2rem);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.65);
    }

    .slot-selection-head {
        padding: .2rem 0 .15rem;
        margin-bottom: .88rem;
    }

    .slot-date-label {
        margin: 0;
        font-size: .73rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #8b7766;
        font-weight: 600;
    }

    .slot-date-value {
        margin: .26rem 0 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.08rem, .95rem + .42vw, 1.24rem);
        color: #3e3228;
    }

    .slot-reassurance {
        margin: .48rem 0 0;
        color: #74675c;
        font-size: .86rem;
        max-width: 62ch;
    }

    .slot-options {
        border: 0;
        padding: 0;
        margin: 0;
    }

    .slot-help-text {
        margin: 0 0 .85rem;
        padding: .72rem .82rem;
        border-radius: 14px;
        border: 1px solid #ebded2;
        background: #fdf8f3;
        color: #6e5f52;
        font-size: .85rem;
    }

    .slot-options-grid {
        gap: .72rem;
    }

    .slot-option {
        position: relative;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: .75rem;
        min-height: 86px;
        padding: .86rem .92rem;
        border: 1px solid #e6d8cb;
        border-radius: 16px;
        background: linear-gradient(180deg, #fffdfb 0%, #fdfaf6 100%);
        box-shadow: 0 8px 18px rgba(109, 87, 66, .04);
        cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease, background-color .2s ease;
    }

    .slot-option:hover {
        border-color: #ddc8b2;
        box-shadow: 0 10px 22px rgba(109, 87, 66, .1);
        transform: translateY(-1px);
    }

    .slot-option:focus-within {
        border-color: #d1b599;
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .14), 0 10px 24px rgba(109, 87, 66, .1);
    }

    .slot-option-input {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        pointer-events: none;
    }

    .slot-option-indicator {
        position: relative;
        width: 1.1rem;
        height: 1.1rem;
        border-radius: 999px;
        border: 1.5px solid #ccb29a;
        background: #fff;
        transition: border-color .2s ease, background-color .2s ease;
    }

    .slot-option-indicator::after {
        content: '';
        position: absolute;
        inset: 2.5px;
        border-radius: 999px;
        transform: scale(.35);
        opacity: 0;
        background: linear-gradient(180deg, #c99f75 0%, var(--accent) 100%);
        transition: transform .2s ease, opacity .2s ease;
    }

    .slot-option-main {
        display: flex;
        flex-direction: column;
        min-width: 0;
        gap: .18rem;
    }

    .slot-option-time {
        font-size: 1.03rem;
        color: #2f2924;
        letter-spacing: .01em;
    }

    .slot-option-caption {
        font-size: .76rem;
        color: #7d6f62;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .slot-option-check {
        padding: .28rem .52rem;
        border-radius: 999px;
        border: 1px solid #dfcdba;
        color: #7a6450;
        font-size: .72rem;
        font-weight: 600;
        opacity: 0;
        transform: translateY(2px);
        transition: opacity .2s ease, transform .2s ease;
        white-space: nowrap;
    }

    .slot-option:has(.slot-option-input:checked) {
        border-color: #d3b596;
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .14), 0 14px 24px rgba(109, 87, 66, .12);
        background: linear-gradient(180deg, #fffdfa 0%, #f9f2eb 100%);
    }

    .slot-option:has(.slot-option-input:checked) .slot-option-indicator {
        border-color: #b7885b;
        background: #fef8f2;
    }

    .slot-option:has(.slot-option-input:checked) .slot-option-indicator::after {
        transform: scale(1);
        opacity: 1;
    }

    .slot-option:has(.slot-option-input:checked) .slot-option-check {
        opacity: 1;
        transform: translateY(0);
        color: #6f5844;
        border-color: #d8c2ad;
        background: #f7efe6;
    }

    .slot-empty {
        grid-column: 1 / -1;
        border: 1px dashed #dcc8b4;
        border-radius: 16px;
        padding: 1.1rem;
        background: #fcf8f3;
        color: #6d5d4f;
    }

    .slot-empty-title {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.06rem;
        color: #4a3c30;
    }

    .slot-empty-copy {
        margin: .42rem 0 .9rem;
        color: #756457;
        font-size: .9rem;
        max-width: 58ch;
    }

    .slot-empty-action {
        min-height: 42px;
    }

    .slot-step-footer {
        margin-top: 1.05rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .9rem;
        flex-wrap: wrap;
    }

    .slot-footer-note {
        margin: 0;
        color: #75695d;
        font-size: .86rem;
        max-width: 49ch;
    }

    .slot-continue {
        min-width: 176px;
        min-height: 46px;
        padding-inline: 1.4rem;
    }

    .slot-continue:disabled {
        opacity: .58;
    }

    @media (max-width: 760px) {
        .slot-option {
            min-height: 80px;
        }

        .slot-step-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .slot-continue {
            width: 100%;
        }

        .slot-option-check {
            font-size: .68rem;
            padding: .24rem .45rem;
        }
    }
</style>

<script>
(() => {
    const button = document.getElementById('slot_submit');
    const radios = [...document.querySelectorAll('input[name="start_time"]')];
    if (!button || radios.length === 0) return;

    const syncButton = () => {
        button.disabled = !radios.some(radio => radio.checked);
    };

    radios.forEach(radio => radio.addEventListener('change', syncButton));
    syncButton();
})();
</script>
@endsection
