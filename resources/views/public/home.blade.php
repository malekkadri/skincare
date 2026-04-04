@extends('public.layouts.app')

@section('title', $settings->site_name)

@section('content')
<style>
    .home-shell {
        display: grid;
        gap: clamp(2rem, 4vw, 3.25rem);
    }
    .home-hero {
        border-radius: 34px;
        border: 1px solid var(--border);
        background: linear-gradient(140deg, #f8efe6 0%, #fbf9f6 55%, #f7f0e8 100%);
        padding: clamp(1.3rem, 3.6vw, 2.2rem);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        position: relative;
    }
    .home-hero::before {
        content: "";
        position: absolute;
        width: min(42vw, 440px);
        aspect-ratio: 1;
        border-radius: 999px;
        top: -18%;
        right: -10%;
        background: radial-gradient(circle, rgba(185, 144, 104, .22), transparent 68%);
        pointer-events: none;
    }
    .hero-grid {
        display: grid;
        grid-template-columns: 1.02fr .98fr;
        gap: clamp(1.2rem, 3vw, 2rem);
        align-items: center;
    }
    .hero-copy {
        display: grid;
        gap: 1rem;
        max-width: 46ch;
    }
    .hero-title {
        font-size: clamp(2.1rem, 5.5vw, 4.1rem);
        line-height: 1.04;
        margin: 0;
        text-wrap: balance;
    }
    .hero-description {
        margin: 0;
        color: var(--text-secondary);
        font-size: 1.04rem;
        line-height: 1.75;
    }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .7rem;
        margin-top: .35rem;
    }
    .hero-trust-list {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
        margin-top: .15rem;
    }
    .hero-trust-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, .74);
        border: 1px solid rgba(218, 205, 191, .9);
        color: #5f5145;
        font-size: .75rem;
        letter-spacing: .09em;
        text-transform: uppercase;
        padding: .42rem .75rem;
    }
    .hero-image-wrap {
        position: relative;
        padding: .65rem;
        border-radius: 28px;
        border: 1px solid #e8dccc;
        background: rgba(255, 255, 255, .52);
    }
    .hero-image-wrap img {
        width: 100%;
        height: clamp(320px, 52vw, 560px);
        border-radius: 20px;
        object-fit: cover;
        display: block;
        box-shadow: 0 20px 36px rgba(63, 44, 28, .16);
    }
    .hero-note {
        position: absolute;
        left: 1.05rem;
        bottom: 1.1rem;
        border-radius: 14px;
        background: rgba(253, 248, 242, .9);
        border: 1px solid rgba(218, 205, 191, .9);
        backdrop-filter: blur(3px);
        padding: .72rem .82rem;
        max-width: 16rem;
    }
    .hero-note strong {
        font-size: .82rem;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .hero-note p {
        margin: .2rem 0 0;
        font-size: .88rem;
        color: var(--text-secondary);
        line-height: 1.45;
    }

    .feature-strip {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .85rem;
    }
    .feature-pill {
        border-radius: 16px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,.85);
        padding: .92rem .85rem;
        display: flex;
        align-items: center;
        gap: .58rem;
        color: #5f5146;
    }
    .feature-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        display: grid;
        place-items: center;
        background: #efe3d7;
        font-size: .92rem;
    }

    .section-shell {
        display: grid;
        gap: 1.2rem;
    }
    .section-head {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: end;
    }
    .services-grid, .gallery-grid, .testimonials-grid {
        display: grid;
        gap: 1.2rem;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .service-card {
        overflow: hidden;
        padding: 0;
        border-radius: 24px;
    }
    .service-card img {
        width: 100%;
        height: 225px;
        object-fit: cover;
        border-radius: 24px 24px 0 0;
        transition: transform .45s ease;
    }
    .service-card:hover img,
    .service-card:focus-visible img { transform: scale(1.045); }
    .service-body {
        padding: 1.15rem;
        position: relative;
    }
    .service-body::before {
        content: "Featured";
        position: absolute;
        top: -1rem;
        right: 1rem;
        background: #f5e8da;
        border: 1px solid #e8d7c6;
        color: #6b5844;
        font-size: .65rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        border-radius: 999px;
        padding: .22rem .55rem;
    }
    .service-body .muted {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .service-meta { color: #8d6e50; font-weight: 600; }

    .about-grid {
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        gap: clamp(1.3rem, 3vw, 2.3rem);
        align-items: center;
    }
    .about-image-wrap {
        border-radius: 28px;
        padding: .85rem;
        border: 1px solid var(--border);
        background: linear-gradient(180deg, #f5eade 0%, #fbf8f4 100%);
    }
    .about-image {
        width: 100%;
        aspect-ratio: .95 / 1;
        border-radius: 20px;
        object-fit: cover;
    }

    .gallery-item {
        border-radius: 22px;
        overflow: hidden;
        min-height: 280px;
        position: relative;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .45s ease;
    }
    .gallery-item::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 35%;
        background: linear-gradient(to top, rgba(38,28,20,.28), transparent);
        opacity: .8;
        pointer-events: none;
    }
    .gallery-item:hover img,
    .gallery-item:focus-within img { transform: scale(1.05); }

    .testimonial-card {
        background: linear-gradient(180deg, #faf3ec 0%, #fffdfb 100%);
        border-color: #e9dbcc;
        padding: 1.4rem;
    }
    .testimonial-card p:first-child {
        margin-top: 0;
        font-size: 1rem;
        line-height: 1.75;
    }
    .testimonial-card h3 {
        margin: .75rem 0 .1rem;
        font-size: 1.02rem;
    }

    .final-cta {
        border-radius: 30px;
        padding: clamp(2rem, 6vw, 4rem) clamp(1rem, 4vw, 2.2rem);
        border: 1px solid #e5d6c5;
        text-align: center;
        background: linear-gradient(128deg, #f0e1d2 0%, #f8f4ef 50%, #f5e8db 100%);
        box-shadow: var(--shadow-soft);
    }
    .final-cta .btn {
        min-width: 12rem;
        margin-top: .35rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .service-card img,
        .gallery-item img,
        .btn { transition: none !important; }
    }
    @media (max-width: 1080px) {
        .hero-grid,
        .about-grid,
        .services-grid,
        .gallery-grid,
        .testimonials-grid,
        .feature-strip { grid-template-columns: 1fr 1fr; }
        .hero-copy { max-width: none; }
    }
    @media (max-width: 700px) {
        .home-shell { gap: 1.6rem; }
        .hero-grid,
        .about-grid,
        .services-grid,
        .gallery-grid,
        .testimonials-grid,
        .feature-strip { grid-template-columns: 1fr; }
        .hero-note { position: static; margin-top: .7rem; max-width: none; }
        .section-head { display: grid; }
        .hero-actions { width: 100%; }
        .hero-actions .btn,
        .hero-actions .btn-soft,
        .btn-row .btn,
        .btn-row .btn-soft { width: 100%; }
    }
</style>

<div class="home-shell">
    <section class="home-hero reveal" aria-label="Hero">
        <div class="hero-grid">
            <div class="hero-copy">
                <p class="section-kicker">{{ __('public.home.hero_kicker') }}</p>
                <h1 class="hero-title">
                    {{ $sections['hero']->localized_title ?? $settings->localized('hero_title') ?? 'Nourish your skin with calm, premium care' }}
                </h1>
                <p class="hero-description">
                    {{ $sections['hero']->localized_content ?? $settings->localized('hero_subtitle') ?? 'Signature facials and advanced skin rituals designed around your skin goals.' }}
                </p>
                <div class="hero-actions">
                    <a href="{{ route('booking.service') }}" class="btn is-gold-focus">{{ __('public.home.hero_cta') }}</a>
                    <a class="btn btn-soft" href="{{ route('services.index') }}">{{ __('public.nav.services') }}</a>
                </div>
                <div class="hero-trust-list" aria-label="Trust indicators">
                    <span class="hero-trust-pill">Licensed Studio</span>
                    <span class="hero-trust-pill">Skin-first Rituals</span>
                    <span class="hero-trust-pill">Private 1:1 Care</span>
                </div>
            </div>
            <div class="hero-image-wrap">
                <img src="https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=1200&q=80" alt="{{ __('public.home.cta_title') }}">
                <div class="hero-note">
                    <strong>Trusted by returning clients</strong>
                    <p>Tailored consultations and intentional treatment plans for healthy, radiant skin.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section" style="padding-top:0; padding-bottom: 0.5rem;">
        <div class="feature-strip reveal">
            @foreach([
                [__('public.home.feature_quick_booking'), '◌'],
                [__('public.home.feature_premium_care'), '✦'],
                [__('public.home.feature_natural_products'), '❋'],
                [__('public.home.feature_trusted_clients'), '♡'],
            ] as [$feature, $icon])
                <article class="feature-pill" aria-label="{{ $feature }}">
                    <span class="feature-icon" aria-hidden="true">{{ $icon }}</span>
                    <span>{{ $feature }}</span>
                </article>
            @endforeach
        </div>
    </section>

    <section class="page-section reveal section-shell">
        <div class="lux-divider"></div>
        <div class="about-grid">
            <div class="about-image-wrap">
                <img class="about-image" src="https://images.unsplash.com/photo-1522337094846-8a818d7b90d3?auto=format&fit=crop&w=900&q=80" alt="Skincare portrait">
            </div>
            <div>
                <p class="section-kicker">{{ __('public.home.about_kicker') }}</p>
                <h2 class="section-title">{{ __('public.home.about_title') }}</h2>
                <p class="muted">{{ __('public.home.about_body') }}</p>
                <div class="btn-row" style="margin-top:1.1rem">
                    <a class="btn btn-soft" href="{{ route('about') }}">{{ __('public.home.about_cta') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section reveal section-shell">
        <div class="lux-divider"></div>
        <div class="section-head">
            <div>
                <p class="section-kicker">{{ __('public.home.services_kicker') }}</p>
                <h2 class="section-title">{{ __('public.home.services_title') }}</h2>
            </div>
            <a class="btn btn-soft" href="{{ route('services.index') }}">{{ __('public.nav.services') }}</a>
        </div>
        <div class="services-grid">
            @forelse($featuredServices as $service)
                <x-service-card :service="$service" />
            @empty
                <article class="card" style="padding:1.2rem">{{ __('public.home.services_empty') }}</article>
            @endforelse
        </div>
    </section>

    <section class="page-section reveal section-shell">
        <div class="lux-divider"></div>
        <div class="section-head">
            <div>
                <p class="section-kicker">{{ __('public.home.gallery_kicker') }}</p>
                <h2 class="section-title">{{ __('public.home.gallery_title') }}</h2>
            </div>
            <a class="btn btn-soft" href="{{ route('gallery') }}">{{ __('public.nav.gallery') }}</a>
        </div>
        <div class="gallery-grid">
            @forelse($featuredGallery as $img)
                <div class="gallery-item card">
                    @if($img->image_url)
                        <img src="{{ $img->image_url }}" alt="{{ $img->localized_title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80" alt="Skincare gallery preview">
                    @endif
                </div>
            @empty
                <article class="card" style="padding:1.2rem">{{ __('public.home.gallery_empty') }}</article>
            @endforelse
        </div>
    </section>

    <section class="page-section reveal section-shell">
        <div class="lux-divider"></div>
        <div class="section-head">
            <div>
                <p class="section-kicker">{{ __('public.home.testimonials_kicker') }}</p>
                <h2 class="section-title">{{ __('public.home.testimonials_title') }}</h2>
            </div>
            <a class="btn btn-soft" href="{{ route('testimonials') }}">{{ __('public.nav.testimonials') }}</a>
        </div>
        <div class="testimonials-grid">
            @forelse($featuredTestimonials as $testimonial)
                <x-testimonial-card :testimonial="$testimonial" />
            @empty
                <article class="card" style="padding:1.2rem">{{ __('public.home.testimonials_empty') }}</article>
            @endforelse
        </div>
    </section>

    <section class="page-section reveal">
        <div class="final-cta">
            <p class="section-kicker">Personalized Skin Journey</p>
            <h2 class="section-title">{{ __('public.home.cta_title') }}</h2>
            <p class="muted">{{ __('public.home.cta_text') }}</p>
            <a class="btn is-gold-focus" href="{{ route('booking.service') }}">{{ __('public.home.cta_button') }}</a>
        </div>
    </section>
</div>
@endsection
