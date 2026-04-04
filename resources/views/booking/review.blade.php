@extends('booking.layouts.app')

@section('content')
@php
    $isFr = ($wizard['locale'] ?? app()->getLocale()) === 'fr';
    $serviceName = $isFr ? $wizard['service']->name_fr : $wizard['service']->name_en;
    $customerName = trim(($wizard['customer']['first_name'] ?? '').' '.($wizard['customer']['last_name'] ?? ''));
    $price = ($wizard['currency'] ?? 'TND') === 'EUR'
        ? number_format((float) $wizard['service']->price_eur, 2).' EUR'
        : number_format((float) $wizard['service']->price_tnd, 2).' TND';
@endphp

<div class="card booking-step-review" data-step-review>
    <div class="step-intro review-step-intro">
        <p class="step-kicker">{{ __('booking.step_review') }}</p>
        <h1 class="title step-title">{{ $isFr ? 'Dernière vérification avant confirmation' : 'Final review before confirmation' }}</h1>
        <p class="subtitle step-subtitle">
            {{ $isFr
                ? 'Merci de relire les détails de votre rendez-vous. Une fois confirmé, Skin by Noor préparera votre visite avec soin.'
                : 'Please review your appointment details below. Once confirmed, Skin by Noor will prepare your visit with care.' }}
        </p>
    </div>

    <section class="review-summary" aria-labelledby="appointment-summary-title">
        <div class="review-summary-head">
            <h2 id="appointment-summary-title" class="review-summary-title">{{ $isFr ? 'Résumé du rendez-vous' : 'Appointment summary' }}</h2>
            <p class="review-summary-copy">{{ $isFr ? 'Vérifiez chaque élément pour confirmer en toute confiance.' : 'Check each detail so you can confirm with confidence.' }}</p>
        </div>

        <dl class="summary-grid" aria-label="{{ $isFr ? 'Détails du rendez-vous' : 'Appointment details' }}">
            <div class="summary-row summary-row-featured">
                <dt>{{ $isFr ? 'Soin sélectionné' : 'Selected service' }}</dt>
                <dd>{{ $serviceName }}</dd>
            </div>
            <div class="summary-row">
                <dt>{{ $isFr ? 'Date' : 'Date' }}</dt>
                <dd>{{ $wizard['appointment_date'] }}</dd>
            </div>
            <div class="summary-row">
                <dt>{{ $isFr ? 'Heure' : 'Time' }}</dt>
                <dd>{{ $wizard['start_time'] }}</dd>
            </div>
            <div class="summary-row">
                <dt>{{ $isFr ? 'Cliente' : 'Client' }}</dt>
                <dd>{{ $customerName }}</dd>
            </div>
            @if(!empty($wizard['customer']['phone']))
                <div class="summary-row">
                    <dt>{{ $isFr ? 'Téléphone' : 'Phone' }}</dt>
                    <dd>{{ $wizard['customer']['phone'] }}</dd>
                </div>
            @endif
            @if(!empty($wizard['customer']['email']))
                <div class="summary-row">
                    <dt>Email</dt>
                    <dd>{{ $wizard['customer']['email'] }}</dd>
                </div>
            @endif
            <div class="summary-row summary-row-price">
                <dt>{{ $isFr ? 'Prix' : 'Price' }}</dt>
                <dd>{{ $price }}</dd>
            </div>
        </dl>
    </section>

    <form method="POST" action="{{ route('booking.confirm') }}" class="review-confirmation-form">
        @csrf

        <section class="confirmation-panel" aria-labelledby="confirmation-title">
            <h2 id="confirmation-title" class="confirmation-title">{{ $isFr ? 'Confirmation' : 'Confirmation' }}</h2>
            <p class="confirmation-copy">
                {{ $isFr
                    ? 'En confirmant, vous validez ces informations et autorisez Skin by Noor à finaliser votre réservation.'
                    : 'By confirming, you verify these details and allow Skin by Noor to finalize your booking.' }}
            </p>

            <label class="confirm-check-row" for="confirm-booking">
                <input id="confirm-booking" type="checkbox" name="confirm" value="1" required>
                <span>{{ $isFr ? 'Je confirme ce rendez-vous.' : 'I confirm this appointment.' }}</span>
            </label>
        </section>

        <div class="review-footer">
            <p class="review-assurance">
                {{ $isFr
                    ? 'Votre demande sera transmise immédiatement après confirmation.'
                    : 'Your request will be submitted immediately after confirmation.' }}
            </p>
            <button class="btn review-submit" type="submit">{{ __('booking.confirm') }}</button>
        </div>
    </form>
</div>

<style>
    .booking-step-review {
        max-width: 860px;
        margin: 0 auto;
        padding: clamp(1.1rem, 2.5vw, 1.95rem);
    }

    .review-step-intro {
        margin-bottom: 1.2rem;
    }

    .step-kicker {
        display: inline-flex;
        align-items: center;
        margin: 0 0 .42rem;
        font-size: .74rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #897768;
        font-weight: 600;
    }

    .step-title {
        margin: 0 0 .35rem;
        font-size: clamp(1.36rem, 1.07rem + 1.03vw, 2.05rem);
    }

    .step-subtitle {
        margin: 0;
        max-width: 68ch;
    }

    .review-summary {
        border: 1px solid #e4d5c8;
        border-radius: 24px;
        background: linear-gradient(180deg, #fffdfb 0%, #fcf7f1 100%);
        padding: clamp(1rem, 2.1vw, 1.35rem);
        margin-bottom: 1rem;
    }

    .review-summary-head {
        margin-bottom: .9rem;
    }

    .review-summary-title {
        margin: 0;
        font-size: clamp(1.08rem, .96rem + .47vw, 1.34rem);
    }

    .review-summary-copy {
        margin: .36rem 0 0;
        color: #736659;
        font-size: .88rem;
    }

    .summary-grid {
        margin: 0;
        display: grid;
        gap: .62rem;
    }

    .summary-row {
        margin: 0;
        display: grid;
        grid-template-columns: minmax(110px, 155px) 1fr;
        align-items: start;
        gap: .55rem;
        border: 1px solid #ebdfd4;
        border-radius: 16px;
        padding: .72rem .84rem;
        background: rgba(255, 253, 250, .9);
    }

    .summary-row dt {
        margin: 0;
        font-size: .8rem;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #847567;
        font-weight: 600;
    }

    .summary-row dd {
        margin: 0;
        font-size: .94rem;
        color: #312b26;
        font-weight: 500;
    }

    .summary-row-featured {
        background: linear-gradient(180deg, #fff9f1 0%, #fffdf9 100%);
        border-color: #dec7b1;
    }

    .summary-row-featured dd {
        font-size: .98rem;
        font-weight: 600;
    }

    .summary-row-price {
        border-color: #dbc4ae;
        background: #fffaf4;
    }

    .summary-row-price dd {
        font-size: 1rem;
        font-weight: 700;
        color: #5e432d;
    }

    .review-confirmation-form {
        display: grid;
        gap: .95rem;
    }

    .confirmation-panel {
        border: 1px solid #e1d1c2;
        border-radius: 20px;
        background: #fffdfb;
        padding: .95rem 1rem;
    }

    .confirmation-title {
        margin: 0;
        font-size: 1.05rem;
    }

    .confirmation-copy {
        margin: .33rem 0 .8rem;
        color: #73675c;
        font-size: .86rem;
        max-width: 65ch;
    }

    .confirm-check-row {
        display: flex;
        gap: .62rem;
        align-items: flex-start;
        padding: .73rem .8rem;
        border-radius: 14px;
        border: 1px solid #e8dccc;
        background: #fffcf9;
        margin: 0;
    }

    .confirm-check-row input[type="checkbox"] {
        width: 1.05rem;
        height: 1.05rem;
        margin-top: .13rem;
        flex-shrink: 0;
        accent-color: #b7885b;
    }

    .confirm-check-row span {
        color: #3a332d;
        font-size: .92rem;
        line-height: 1.45;
    }

    .review-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .8rem;
        flex-wrap: wrap;
        padding-top: .1rem;
    }

    .review-assurance {
        margin: 0;
        color: #6e6054;
        font-size: .84rem;
    }

    .review-submit {
        min-width: min(100%, 230px);
    }

    @media (max-width: 760px) {
        .booking-step-review {
            padding: 1rem;
        }

        .summary-row {
            grid-template-columns: 1fr;
            gap: .28rem;
            padding: .67rem .74rem;
        }

        .summary-row dt {
            font-size: .73rem;
        }

        .review-footer {
            position: sticky;
            bottom: .4rem;
            background: rgba(247, 243, 238, .94);
            border: 1px solid #e8dccf;
            border-radius: 16px;
            padding: .75rem;
            backdrop-filter: blur(5px);
        }

        .review-submit {
            width: 100%;
        }
    }
</style>
@endsection
