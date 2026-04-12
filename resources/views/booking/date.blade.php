@extends('booking.layouts.app')

@section('content')
<div class="card booking-step-date" style="max-width:680px;" data-step-date>
    <div class="step-intro date-step-intro">
        <p class="step-kicker">{{ __('booking.step_date') }}</p>
        <h1 class="title step-title">{{ __('booking.date_heading') }}</h1>
        <p class="subtitle step-subtitle">{{ __('booking.date_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('booking.date.save') }}" data-date-form>
        @csrf

        <div class="date-selection-shell">
            <label for="appointment_date">{{ __('booking.date_label') }}</label>
            <input
                type="date"
                id="appointment_date"
                name="appointment_date"
                min="{{ now()->toDateString() }}"
                value="{{ old('appointment_date', $wizard['appointment_date']) }}"
                required
                aria-describedby="date-reassurance availability_hint selected_date_summary"
            >

            @error('appointment_date')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <p id="date-reassurance" class="date-reassurance">{{ __('booking.date_reassurance') }}</p>

            <p id="selected_date_summary" class="selected-date-summary" aria-live="polite"></p>

            <p
                id="availability_hint"
                class="availability-hint"
                role="status"
                aria-live="polite"
                aria-atomic="true"
                data-state="idle"
            ></p>
        </div>

        <div class="date-step-footer">
            <p class="date-footer-note">{{ __('booking.date_footer_note') }}</p>
            <button class="btn date-continue" type="submit" data-date-continue>{{ __('booking.continue') }}</button>
        </div>
    </form>
</div>

<style>
    .booking-step-date {
        padding: clamp(1.1rem, 2.3vw, 1.75rem);
    }

    .date-step-intro {
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
        margin: 0 0 .35rem;
        font-size: clamp(1.35rem, 1.1rem + 1vw, 2rem);
    }

    .step-subtitle {
        margin-bottom: 0;
        max-width: 62ch;
    }

    .date-selection-shell {
        border: 1px solid #e8dacd;
        border-radius: 20px;
        background: linear-gradient(180deg, #fffdfb 0%, #fbf6f0 100%);
        padding: clamp(.95rem, 2.1vw, 1.2rem);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.65);
    }

    .date-selection-shell input[type="date"] {
        min-height: 48px;
        border-color: #d9c7b6;
        background: #fffdfa;
    }

    .date-selection-shell input[type="date"]:focus-visible {
        outline-color: rgba(183, 136, 91, .55);
        box-shadow: 0 0 0 3px rgba(183, 136, 91, .14);
    }

    .date-reassurance {
        margin: .75rem 0 0;
        color: #75685d;
        font-size: .85rem;
    }

    .selected-date-summary {
        display: none;
        margin: .8rem 0 0;
        padding: .7rem .82rem;
        border-radius: 14px;
        border: 1px solid #e2d1bf;
        background: #fdf8f2;
        color: #6f5d4d;
        font-size: .88rem;
    }

    .selected-date-summary.is-visible {
        display: block;
    }

    .availability-hint {
        margin: .72rem 0 0;
        padding: .72rem .82rem;
        border-radius: 14px;
        border: 1px solid transparent;
        background: #faf6f1;
        color: #75685c;
        font-size: .88rem;
        min-height: 2.45rem;
        display: flex;
        align-items: center;
    }

    .availability-hint[data-state="idle"] {
        background: #faf6f1;
        border-color: #ece1d5;
        color: #7c6f63;
    }

    .availability-hint[data-state="loading"] {
        background: #f7f1ea;
        border-color: #e8d8c9;
        color: #7c6650;
    }

    .availability-hint[data-state="available"] {
        background: #f2f8f4;
        border-color: #cde3d5;
        color: #345e49;
    }

    .availability-hint[data-state="empty"] {
        background: #fdf6ef;
        border-color: #ecd9c7;
        color: #7a604a;
    }

    .availability-hint[data-state="error"] {
        background: #fdf2f2;
        border-color: #efc9c9;
        color: #8a4444;
    }

    .date-step-footer {
        margin-top: 1.05rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .9rem;
        flex-wrap: wrap;
    }

    .date-footer-note {
        margin: 0;
        color: #75695d;
        font-size: .86rem;
        max-width: 46ch;
    }

    .date-continue {
        min-width: 176px;
        min-height: 46px;
        padding-inline: 1.4rem;
    }

    .date-continue:disabled {
        opacity: .58;
    }

    @media (max-width: 760px) {
        .date-step-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .date-continue {
            width: 100%;
        }
    }
</style>

<script>
(() => {
    const dateInput = document.getElementById('appointment_date');
    const hint = document.getElementById('availability_hint');
    const summary = document.getElementById('selected_date_summary');
    const continueButton = document.querySelector('[data-date-continue]');
    const serviceId = @json($wizard['service']?->id);
    const endpoint = @json(route('booking.available-slots'));

    const setHint = (message, state) => {
        hint.textContent = message;
        hint.dataset.state = state;
    };

    const updateSelectedDateSummary = () => {
        if (!dateInput.value) {
            summary.textContent = '';
            summary.classList.remove('is-visible');
            continueButton.disabled = true;
            setHint(@json(__('booking.date_hint_idle')), 'idle');
            return;
        }

        const date = new Date(`${dateInput.value}T00:00:00`);
        const formattedDate = Number.isNaN(date.getTime())
            ? dateInput.value
            : new Intl.DateTimeFormat(document.documentElement.lang || undefined, {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric',
            }).format(date);

        summary.textContent = `${@json(__('booking.slot_date_label'))}: ${formattedDate}`;
        summary.classList.add('is-visible');
        continueButton.disabled = false;
    };

    const updateHint = async () => {
        updateSelectedDateSummary();

        if (!dateInput.value || !serviceId) {
            return;
        }

        setHint(@json(__('booking.date_hint_loading')), 'loading');

        try {
            const query = new URLSearchParams({ service_id: String(serviceId), date: dateInput.value });
            const response = await fetch(`${endpoint}?${query.toString()}`, { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            const slots = Array.isArray(payload.slots) ? payload.slots : [];

            if (slots.length) {
                setHint(@json(__('booking.date_hint_available', ['count' => '__COUNT__'])).replace('__COUNT__', String(slots.length)), 'available');
            } else {
                setHint(@json(__('booking.date_hint_empty')), 'empty');
            }
        } catch (error) {
            setHint(@json(__('booking.date_hint_error')), 'error');
        }
    };

    dateInput.addEventListener('change', updateHint);
    updateHint();
})();
</script>
@endsection
