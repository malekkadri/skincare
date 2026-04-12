@extends('public.layouts.app')

@section('title', 'Services')

@section('content')
@php
    $totalServices = $categories->sum(fn($category) => $category->services->count());
@endphp

<style>
    .services-page {
        display: grid;
        gap: clamp(2.4rem, 4vw, 3.5rem);
    }

    .services-hero {
        position: relative;
        overflow: hidden;
    }

    .services-hero::after {
        content: '';
        position: absolute;
        inset: auto -8% -35% auto;
        width: clamp(180px, 26vw, 320px);
        aspect-ratio: 1;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(185, 144, 104, .2) 0%, rgba(185, 144, 104, 0) 68%);
        pointer-events: none;
    }

    .services-hero p {
        max-width: 66ch;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .services-hero-meta {
        margin-top: 1.2rem;
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
        position: relative;
        z-index: 1;
    }

    .services-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .5rem .78rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, .82);
        border: 1px solid var(--border-strong);
        font-size: .78rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .services-categories {
        display: grid;
        gap: clamp(2.2rem, 4vw, 3.6rem);
    }

    .service-category {
        display: grid;
        gap: 1.25rem;
    }

    .service-category-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: baseline;
        gap: .85rem;
        padding-bottom: .85rem;
        border-bottom: 1px solid rgba(218, 205, 191, .9);
    }

    .service-category-title {
        margin: 0;
        font-size: clamp(1.36rem, 2vw, 1.8rem);
    }

    .service-category-count {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .14em;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .service-card {
        --service-card-padding: 1.2rem;
        display: grid;
        gap: .95rem;
        min-height: 100%;
        border-radius: 22px;
        background: linear-gradient(180deg, rgba(255, 255, 255, .98), #fffaf5);
        border: 1px solid var(--border);
        box-shadow: 0 12px 30px rgba(47, 39, 34, .08);
        padding: var(--service-card-padding) var(--service-card-padding) 1.1rem;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .service-card:hover,
    .service-card:focus-visible {
        transform: translateY(-3px);
        border-color: var(--border-strong);
        box-shadow: 0 18px 36px rgba(47, 39, 34, .12);
    }

    .service-card-media {
        margin: calc(var(--service-card-padding) * -1) calc(var(--service-card-padding) * -1) 0;
        border-radius: 22px 22px 0 0;
        overflow: hidden;
        position: relative;
        aspect-ratio: 16 / 10;
        background: #f2e6da;
    }

    .service-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .35s ease;
    }

    .service-card:hover .service-card-media img,
    .service-card:focus-visible .service-card-media img {
        transform: scale(1.05);
    }

    .service-card-media::after {
        content: '';
        position: absolute;
        inset: auto 0 0;
        height: 50%;
        background: linear-gradient(to top, rgba(44, 30, 18, .24), transparent);
        pointer-events: none;
    }

    .service-card-header {
        display: grid;
        gap: .45rem;
    }

    .service-card-title {
        margin: 0;
        font-size: 1.13rem;
        line-height: 1.35;
    }

    .service-card-copy {
        margin: 0;
        color: var(--text-secondary);
        line-height: 1.65;
    }

    .service-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
        margin-top: auto;
    }

    .service-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: rgba(252, 247, 242, .9);
        color: #6f604f;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .02em;
        padding: .34rem .65rem;
        white-space: nowrap;
    }

    .service-meta-chip svg {
        width: .88rem;
        height: .88rem;
        stroke: currentColor;
    }

    .service-card-cta {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        color: var(--accent-deep);
        font-weight: 600;
        letter-spacing: .04em;
        font-size: .76rem;
        text-transform: uppercase;
    }

    .service-card-cta svg {
        width: .86rem;
        height: .86rem;
        transition: transform .2s ease;
    }

    .service-card:hover .service-card-cta svg,
    .service-card:focus-visible .service-card-cta svg {
        transform: translateX(3px);
    }

    @media (max-width: 1000px) {
        .services-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .services-grid {
            grid-template-columns: 1fr;
            gap: .85rem;
        }

        .service-card {
            --service-card-padding: 1.05rem;
            padding: 1.05rem;
            gap: .82rem;
        }

        .service-card-title {
            font-size: 1.04rem;
        }

        .services-hero-meta {
            gap: .5rem;
        }

        .services-hero-pill {
            font-size: .72rem;
            padding: .45rem .62rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .service-card,
        .service-card-cta svg {
            transition: none;
        }
    }
</style>

<section class="page-section services-page">
    <div class="page-hero services-hero">
        <p class="section-kicker">Services</p>
        <h1 class="section-title">Signature skincare rituals</h1>
        <p class="muted">Explore every treatment by concern and category with transparent timing and pricing, so you can choose the ritual that fits your skin goals with confidence.</p>
        <div class="services-hero-meta" aria-label="Service overview">
            <span class="services-hero-pill">{{ $categories->count() }} Categories</span>
            <span class="services-hero-pill">{{ $totalServices }} Services</span>
            <span class="services-hero-pill">Tailored treatment paths</span>
        </div>
    </div>

    <div class="services-categories">
        @forelse($categories as $category)
            <section class="service-category" aria-labelledby="category-{{ $category->id }}">
                <div class="service-category-header">
                    <h2 class="service-category-title" id="category-{{ $category->id }}">{{ $category->localized_name }}</h2>
                    <span class="service-category-count">{{ $category->services->count() }} services</span>
                </div>

                <div class="services-grid">
                    @forelse($category->services as $service)
                        <a class="service-card reveal is-gold-focus" href="{{ route('services.show', $service->slug) }}" aria-label="View details for {{ $service->localized_name }}">
                            <figure class="service-card-media">
                                <img
                                    src="{{ $service->image_url ?: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80' }}"
                                    alt="{{ $service->localized_name }}"
                                    loading="lazy"
                                >
                            </figure>
                            <div class="service-card-header">
                                <h3 class="service-card-title">{{ $service->localized_name }}</h3>
                                <p class="service-card-copy">{{ $service->localized_short_description }}</p>
                            </div>

                            <div class="service-card-meta" aria-label="Service details">
                                <span class="service-meta-chip">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
                                    {{ $service->duration_minutes }} min
                                </span>
                                <span class="service-meta-chip">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" aria-hidden="true"><path d="M12 2v20"></path><path d="M17 6.5c0-1.8-2.2-3-5-3s-5 1.2-5 3 2.2 3 5 3 5 1.2 5 3-2.2 3-5 3-5-1.2-5-3"></path></svg>
                                    {{ $service->display_price }}
                                </span>
                            </div>

                            <span class="service-card-cta">
                                View details
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg>
                            </span>
                        </a>
                    @empty
                        <div class="empty-state form-span-full">Services for this category will be published soon.</div>
                    @endforelse
                </div>
            </section>
        @empty
            <div class="empty-state">Services will be published soon.</div>
        @endforelse
    </div>
</section>
@endsection
