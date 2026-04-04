@extends('booking.layouts.app')

@section('content')
@php
    $isFr = app()->getLocale() === 'fr';
    $serviceName = $isFr
        ? ($appointment->service_name_snapshot_fr ?: $appointment->service_name_snapshot_en)
        : ($appointment->service_name_snapshot_en ?: $appointment->service_name_snapshot_fr);
    $dateLabel = $appointment->appointment_date?->format('Y-m-d');
    $timeLabel = \Illuminate\Support\Str::substr((string) $appointment->start_time, 0, 5);
@endphp

<div class="card booking-step-success" data-step-success>
    <header class="success-hero" aria-labelledby="success-title">
        <div class="success-badge" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" role="presentation" focusable="false">
                <path d="M20 7L10.6 16.4L7.3 13.1" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <p class="success-kicker">{{ $isFr ? 'Réservation validée' : 'Booking confirmed' }}</p>
        <h1 id="success-title" class="success-title">{{ __('booking.success') }}</h1>
        <p class="success-subtitle">
            {{ $isFr
                ? 'Merci de votre confiance. Votre rendez-vous Skin by Noor est confirmé et préparé avec attention.'
                : 'Thank you for your trust. Your Skin by Noor appointment is confirmed and being prepared with care.' }}
        </p>
    </header>

    <section class="success-summary" aria-labelledby="success-summary-title">
        <h2 id="success-summary-title" class="sr-only">{{ $isFr ? 'Détails de confirmation' : 'Confirmation details' }}</h2>

        <div class="success-reference" aria-label="{{ $isFr ? 'Référence du rendez-vous' : 'Appointment reference' }}">
            <p class="reference-label">{{ $isFr ? 'Référence du rendez-vous' : 'Appointment reference' }}</p>
            <p class="reference-value">#{{ $appointment->id }}</p>
            <p class="reference-note">
                {{ $isFr
                    ? 'Conservez cette référence pour toute demande liée à votre réservation.'
                    : 'Keep this reference for any question related to your booking.' }}
            </p>
        </div>

        <dl class="success-details" aria-label="{{ $isFr ? 'Résumé du rendez-vous' : 'Appointment summary' }}">
            <div class="success-detail-row success-detail-featured">
                <dt>{{ $isFr ? 'Soin' : 'Service' }}</dt>
                <dd>{{ $serviceName }}</dd>
            </div>
            <div class="success-detail-row">
                <dt>{{ $isFr ? 'Date' : 'Date' }}</dt>
                <dd>{{ $dateLabel }}</dd>
            </div>
            <div class="success-detail-row">
                <dt>{{ $isFr ? 'Heure' : 'Time' }}</dt>
                <dd>{{ $timeLabel }}</dd>
            </div>
        </dl>
    </section>

    <section class="success-next" aria-labelledby="next-steps-title">
        <h2 id="next-steps-title" class="next-title">{{ $isFr ? 'Et ensuite ?' : 'What happens next?' }}</h2>
        <p class="next-copy">
            {{ $isFr
                ? 'Skin by Noor reviendra vers vous si une précision est nécessaire. En attendant, votre rendez-vous est bien enregistré.'
                : 'Skin by Noor will contact you if any additional detail is needed. In the meantime, your appointment is securely recorded.' }}
        </p>
    </section>

    <div class="success-actions">
        <a class="btn success-primary-action" href="{{ route('booking.service') }}">{{ $isFr ? 'Nouvelle réservation' : 'New booking' }}</a>
    </div>
</div>

<style>
    .booking-step-success {
        max-width: 820px;
        margin: 0 auto;
        padding: clamp(1.15rem, 2.6vw, 2rem);
        display: grid;
        gap: 1rem;
    }

    .success-hero {
        text-align: center;
        display: grid;
        justify-items: center;
        gap: .5rem;
        margin-bottom: .15rem;
    }

    .success-badge {
        width: clamp(3rem, 8vw, 4.1rem);
        height: clamp(3rem, 8vw, 4.1rem);
        border-radius: 999px;
        display: grid;
        place-items: center;
        color: #7e5a3e;
        border: 1px solid #dcc4ae;
        background: radial-gradient(circle at 30% 30%, #fff8ee 0%, #faefe3 65%, #f5e6d5 100%);
        box-shadow: 0 12px 30px rgba(118, 84, 57, .14);
    }

    .success-badge svg {
        width: 1.45rem;
        height: 1.45rem;
    }

    .success-kicker {
        margin: 0;
        font-size: .72rem;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #8b7663;
        font-weight: 600;
    }

    .success-title {
        margin: 0;
        font-size: clamp(1.45rem, 1.12rem + 1.24vw, 2.25rem);
        line-height: 1.2;
    }

    .success-subtitle {
        margin: 0;
        max-width: 64ch;
        color: #6f6359;
        font-size: clamp(.9rem, .84rem + .26vw, 1.02rem);
        line-height: 1.6;
    }

    .success-summary {
        display: grid;
        gap: .85rem;
        border: 1px solid #e5d7ca;
        border-radius: 24px;
        background: linear-gradient(180deg, #fffdfa 0%, #fdf8f2 100%);
        padding: clamp(1rem, 2.2vw, 1.35rem);
    }

    .success-reference {
        border: 1px solid #dfcbb7;
        border-radius: 18px;
        background: #fffaf4;
        padding: .95rem 1rem;
        text-align: center;
    }

    .reference-label {
        margin: 0;
        font-size: .74rem;
        text-transform: uppercase;
        letter-spacing: .13em;
        color: #8f7866;
        font-weight: 600;
    }

    .reference-value {
        margin: .3rem 0;
        font-size: clamp(1.28rem, 1.03rem + .95vw, 1.9rem);
        line-height: 1.2;
        font-weight: 700;
        color: #4d3928;
    }

    .reference-note {
        margin: 0;
        color: #716356;
        font-size: .84rem;
    }

    .success-details {
        margin: 0;
        display: grid;
        gap: .58rem;
    }

    .success-detail-row {
        margin: 0;
        display: grid;
        gap: .45rem;
        grid-template-columns: minmax(90px, 140px) 1fr;
        align-items: center;
        padding: .7rem .82rem;
        border-radius: 14px;
        border: 1px solid #eaded2;
        background: rgba(255, 255, 255, .88);
    }

    .success-detail-row dt {
        margin: 0;
        font-size: .77rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #877668;
        font-weight: 600;
    }

    .success-detail-row dd {
        margin: 0;
        color: #332c26;
        font-size: .93rem;
        font-weight: 500;
    }

    .success-detail-featured {
        border-color: #dec8b3;
        background: #fffaf3;
    }

    .success-detail-featured dd {
        font-weight: 600;
    }

    .success-next {
        border: 1px solid #e7dbcf;
        border-radius: 18px;
        background: #fffdfb;
        padding: .95rem 1rem;
    }

    .next-title {
        margin: 0;
        font-size: 1.02rem;
    }

    .next-copy {
        margin: .42rem 0 0;
        font-size: .88rem;
        line-height: 1.55;
        color: #6d6258;
        max-width: 70ch;
    }

    .success-actions {
        display: flex;
        justify-content: center;
        padding-top: .2rem;
    }

    .success-primary-action {
        min-width: min(100%, 250px);
        text-align: center;
        justify-content: center;
    }

    @media (max-width: 700px) {
        .booking-step-success {
            gap: .85rem;
        }

        .success-summary,
        .success-next {
            border-radius: 20px;
        }

        .success-reference {
            border-radius: 16px;
        }

        .success-detail-row {
            grid-template-columns: 1fr;
            gap: .2rem;
            padding: .7rem .74rem;
        }

        .success-detail-row dt {
            font-size: .72rem;
        }

        .success-primary-action {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .success-badge,
        .success-primary-action {
            transition: none;
        }
    }
</style>
@endsection
