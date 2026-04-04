@extends('public.layouts.app')

@section('title', $settings->site_name)

@section('content')
<style>
    .hero-grid {
        display: grid;
        align-items: center;
        gap: 2.25rem;
        grid-template-columns: 1.1fr 1fr;
    }
    .hero-image-wrap { padding: .9rem; }
    .hero-image-wrap img {
        width: 100%;
        height: 540px;
        object-fit: cover;
        border-radius: 20px;
    }
    .hero-title {
        font-size: clamp(2.2rem, 5vw, 4rem);
        line-height: 1.1;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .features-bar {
        background: #f4f0ea;
        border-radius: 24px;
        padding: 1.1rem;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .9rem;
    }
    .feature-pill {
        background: #fffaf6;
        border: 1px solid #ebe0d6;
        border-radius: 16px;
        padding: .95rem;
        display: flex;
        gap: .7rem;
        align-items: center;
        font-size: .94rem;
    }
    .feature-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: #f1e6dc;
    }
    .about-grid {
        display: grid;
        gap: 2rem;
        align-items: center;
        grid-template-columns: .95fr 1.05fr;
    }
    .about-image {
        width: min(440px, 100%);
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        object-fit: cover;
        border: 10px solid #efe7de;
    }
    .services-grid, .gallery-grid, .testimonials-grid {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .service-card { overflow: hidden; }
    .service-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 22px 22px 0 0;
    }
    .service-body { padding: 1.15rem; }
    .service-body h3 { margin-bottom: .35rem; }
    .service-meta { color: #8b6e52; font-weight: 500; }
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        min-height: 280px;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
        border-radius: 20px;
    }
    .gallery-item:hover img { transform: scale(1.06); }
    .testimonial-card {
        padding: 1.35rem;
        background: #f8f1ea;
        border-color: #e9ddd1;
    }
    .testimonial-card h3 { margin-bottom: .2rem; }
    .cta-block {
        text-align: center;
        background: linear-gradient(135deg, #f2e9df 0%, #f7f3ed 100%);
        border: 1px solid #e7dbcf;
        border-radius: 28px;
        padding: 4rem 1rem;
    }
    @media (max-width: 980px) {
        .hero-grid,
        .about-grid,
        .services-grid,
        .gallery-grid,
        .testimonials-grid,
        .features-bar { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 700px) {
        .hero-grid,
        .about-grid,
        .services-grid,
        .gallery-grid,
        .testimonials-grid,
        .features-bar { grid-template-columns: 1fr; }
        .hero-image-wrap img { height: 390px; }
    }
</style>

<x-hero
    :title="$sections['hero']->localized_title ?? $settings->localized('hero_title') ?? 'Nourish your skin with calm, premium care'"
    :description="$sections['hero']->localized_content ?? $settings->localized('hero_subtitle') ?? 'Signature facials and advanced skin rituals designed around your skin goals.'"
    image="https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=1200&q=80"
    :cta-url="route('booking.service')"
    cta-label="Book Now"
/>

<section class="page-section" style="padding-top:0">
    <div class="features-bar">
        @foreach([
            ['Quick booking', '◌'],
            ['Premium care', '✦'],
            ['Natural products', '❋'],
            ['Trusted clients', '♡'],
        ] as [$feature, $icon])
            <div class="feature-pill">
                <span class="feature-icon" aria-hidden="true">{{ $icon }}</span>
                <span>{{ $feature }}</span>
            </div>
        @endforeach
    </div>
</section>

<section class="page-section">
    <div class="about-grid">
        <img class="about-image" src="https://images.unsplash.com/photo-1522337094846-8a818d7b90d3?auto=format&fit=crop&w=900&q=80" alt="Skincare portrait">
        <div>
            <p class="section-kicker">About</p>
            <h2 class="section-title">Who are we</h2>
            <p class="muted">At Skin by Noor, we combine modern techniques with gentle rituals to help your skin glow naturally. Every appointment is crafted with intention, comfort, and precision.</p>
            <a class="btn btn-soft" href="{{ route('about') }}">Discover Our Story</a>
        </div>
    </div>
</section>

<section class="page-section">
    <p class="section-kicker">Services</p>
    <h2 class="section-title">Signature treatments</h2>
    <div class="services-grid">
        @forelse($featuredServices as $service)
            <x-service-card :service="$service" />
        @empty
            <article class="card" style="padding:1.2rem">Services will appear here once published.</article>
        @endforelse
    </div>
</section>

<section class="page-section">
    <p class="section-kicker">Gallery</p>
    <h2 class="section-title">Glow moments</h2>
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
            <article class="card" style="padding:1.2rem">Gallery images will appear here once published.</article>
        @endforelse
    </div>
</section>

<section class="page-section">
    <p class="section-kicker">Testimonials</p>
    <h2 class="section-title">Loved by our clients</h2>
    <div class="testimonials-grid">
        @forelse($featuredTestimonials as $testimonial)
            <x-testimonial-card :testimonial="$testimonial" />
        @empty
            <article class="card" style="padding:1.2rem">Testimonials will appear here after client feedback is published.</article>
        @endforelse
    </div>
</section>

<section class="page-section">
    <div class="cta-block">
        <h2 class="section-title">Book your skincare experience</h2>
        <p class="muted">Reserve your spot for personalized treatment and calm, luxury care.</p>
        <a class="btn" href="{{ route('booking.service') }}">Start Booking</a>
    </div>
</section>
@endsection
