@extends('public.layouts.app')

@section('title', $service->localized_name)

@section('content')
<style>
    .service-detail-page {
        display: grid;
        gap: clamp(1.8rem, 3.4vw, 2.8rem);
    }

    .service-detail-hero {
        position: relative;
        overflow: hidden;
        display: grid;
        gap: clamp(1.4rem, 2.8vw, 2.2rem);
    }

    .service-detail-hero::before {
        content: '';
        position: absolute;
        inset: auto -12% -48% auto;
        width: clamp(210px, 28vw, 360px);
        aspect-ratio: 1;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(185, 144, 104, .2) 0%, rgba(185, 144, 104, 0) 70%);
        pointer-events: none;
    }

    .service-detail-lead {
        max-width: 64ch;
        margin: 0;
        position: relative;
        z-index: 1;
        color: var(--text-secondary);
    }

    .service-detail-grid {
        display: grid;
        gap: .85rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        position: relative;
        z-index: 1;
    }

    .service-detail-meta {
        border-radius: 18px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, .75);
        padding: .95rem 1rem;
        display: grid;
        gap: .24rem;
    }

    .service-detail-meta-label {
        margin: 0;
        text-transform: uppercase;
        letter-spacing: .12em;
        font-size: .71rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .service-detail-meta-value {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.15rem, 2.2vw, 1.45rem);
        line-height: 1.2;
        color: var(--text-primary);
    }

    .service-detail-actions {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .9rem;
    }

    .service-detail-cta {
        padding-inline: 1.55rem;
        letter-spacing: .08em;
    }

    .service-detail-assurance {
        margin: 0;
        color: var(--text-secondary);
        font-size: .9rem;
    }

    .service-detail-content {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(240px, .7fr);
        gap: clamp(1rem, 2.4vw, 1.4rem);
        align-items: start;
    }

    .service-detail-visual {
        border-radius: 24px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, .86);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        position: relative;
        isolation: isolate;
        min-height: clamp(260px, 36vw, 420px);
    }

    .service-detail-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scale(1.01);
        animation: detailImagePulse 8s ease-in-out infinite;
    }

    .service-detail-visual::after {
        content: '';
        position: absolute;
        inset: auto 0 0;
        height: 38%;
        background: linear-gradient(to top, rgba(31, 22, 17, .4), rgba(31, 22, 17, 0));
        pointer-events: none;
    }

    .service-detail-visual-caption {
        position: absolute;
        z-index: 1;
        left: 1rem;
        bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, .36);
        background: rgba(255, 255, 255, .12);
        backdrop-filter: blur(4px);
        color: #fff;
        border-radius: 999px;
        letter-spacing: .07em;
        text-transform: uppercase;
        font-size: .68rem;
        font-weight: 600;
        padding: .45rem .72rem;
    }

    .service-detail-body,
    .service-detail-support {
        border-radius: 24px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, .86);
        box-shadow: var(--shadow-soft);
    }

    .service-detail-body {
        padding: clamp(1.25rem, 2.6vw, 1.9rem);
    }

    .service-detail-body h2,
    .service-detail-support h2 {
        margin: 0;
        font-size: clamp(1.25rem, 2.2vw, 1.58rem);
    }

    .service-detail-copy {
        margin: .9rem 0 0;
        color: var(--text-secondary);
        line-height: 1.86;
        white-space: pre-line;
    }

    .service-detail-support {
        display: grid;
        gap: 1.15rem;
        padding: clamp(1.1rem, 2.2vw, 1.45rem);
    }

    .service-detail-list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: .7rem;
    }

    .service-detail-list li {
        display: flex;
        gap: .6rem;
        color: var(--text-secondary);
        line-height: 1.55;
        font-size: .94rem;
    }

    .service-detail-list li::before {
        content: '';
        flex: 0 0 .42rem;
        height: .42rem;
        border-radius: 50%;
        margin-top: .5rem;
        background: rgba(185, 144, 104, .85);
        box-shadow: 0 0 0 5px rgba(185, 144, 104, .16);
    }

    .service-detail-secondary-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    @keyframes detailImagePulse {
        0%, 100% { transform: scale(1.01); }
        50% { transform: scale(1.05); }
    }

    @media (max-width: 900px) {
        .service-detail-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .service-detail-grid {
            grid-template-columns: 1fr;
        }

        .service-detail-actions {
            align-items: stretch;
            gap: .7rem;
        }

        .service-detail-cta {
            width: 100%;
        }

        .service-detail-assurance {
            font-size: .85rem;
        }

        .service-detail-body,
        .service-detail-support {
            border-radius: 20px;
        }

        .service-detail-copy {
            line-height: 1.78;
        }

        .service-detail-visual-caption {
            font-size: .62rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .service-detail-hero,
        .service-detail-meta,
        .service-detail-body,
        .service-detail-support,
        .service-detail-cta,
        .service-detail-visual img {
            transition: none;
            animation: none;
        }
    }
</style>

<section class="page-section service-detail-page">
    <header class="page-hero service-detail-hero">
        <p class="section-kicker">Service details</p>
        <h1 class="section-title">{{ $service->localized_name }}</h1>
        <p class="service-detail-lead">{{ $service->localized_description }}</p>

        <div class="service-detail-grid" aria-label="Service quick facts">
            <article class="service-detail-meta">
                <p class="service-detail-meta-label">Investment</p>
                <p class="service-detail-meta-value">{{ $service->display_price }}</p>
            </article>
            <article class="service-detail-meta">
                <p class="service-detail-meta-label">Treatment time</p>
                <p class="service-detail-meta-value">{{ $service->duration_minutes }} min</p>
            </article>
        </div>

        <div class="service-detail-actions">
            <a class="btn service-detail-cta is-gold-focus" href="{{ route('booking.service') }}">Book now</a>
            <p class="service-detail-assurance">Begin your booking in under a minute. You can review and confirm your appointment details before final submission.</p>
        </div>
    </header>

    <section class="service-detail-visual reveal" aria-label="Service image">
        <img
            src="{{ $service->image_url ?: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=1400&q=80' }}"
            alt="{{ $service->localized_name }}"
        >
        <span class="service-detail-visual-caption">Personalized service</span>
    </section>

    <section class="service-detail-content" aria-label="Service information">
        <article class="service-detail-body">
            <h2>What to expect</h2>
            <p class="service-detail-copy">{{ $service->localized_description }}</p>
        </article>

        <aside class="service-detail-support" aria-label="Booking support">
            <div>
                <h2>Your next step</h2>
                <ul class="service-detail-list">
                    <li>Choose this service in booking and select your preferred date and time.</li>
                    <li>Arrive a few minutes early so your treatment can begin feeling calm and unrushed.</li>
                    <li>If you are unsure this is right for you, request guidance during booking for a tailored recommendation.</li>
                </ul>
            </div>

            <a class="btn btn-soft service-detail-secondary-cta is-gold-focus" href="{{ route('booking.service') }}">Continue to booking</a>
        </aside>
    </section>
</section>
@endsection
