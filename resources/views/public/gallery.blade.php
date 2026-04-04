@extends('public.layouts.app')

@section('content')
<style>
    .gallery-shell {
        display: grid;
        gap: clamp(1.6rem, 3.2vw, 2.4rem);
    }
    .gallery-hero {
        position: relative;
        overflow: hidden;
    }
    .gallery-hero::after {
        content: "";
        position: absolute;
        right: -8%;
        top: -28%;
        width: min(40vw, 360px);
        aspect-ratio: 1;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(185, 144, 104, .18) 0%, transparent 70%);
        pointer-events: none;
    }
    .gallery-hero-copy {
        max-width: 62ch;
        position: relative;
        z-index: 1;
    }
    .gallery-hero-copy .muted {
        margin-bottom: 0;
        line-height: 1.8;
    }
    .gallery-support-row {
        margin-top: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
    }
    .gallery-support-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: rgba(255, 255, 255, .72);
        border: 1px solid var(--border-strong);
        color: #685849;
        font-size: .72rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-weight: 600;
        padding: .4rem .72rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: clamp(1rem, 2vw, 1.35rem);
    }
    .gallery-card {
        overflow: hidden;
        padding: 0;
        border-radius: 24px;
        background: var(--surface-strong);
    }
    .gallery-media-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 24px 24px 0 0;
        isolation: isolate;
    }
    .gallery-media-wrap::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(35, 25, 18, .26), transparent 58%);
        opacity: .8;
        pointer-events: none;
        transition: opacity .3s ease;
    }
    .gallery-image {
        width: 100%;
        height: clamp(230px, 28vw, 300px);
        object-fit: cover;
        display: block;
        transition: transform .5s ease;
    }
    .gallery-card:hover .gallery-image,
    .gallery-card:focus-within .gallery-image {
        transform: scale(1.04);
    }
    .gallery-card:hover .gallery-media-wrap::after,
    .gallery-card:focus-within .gallery-media-wrap::after {
        opacity: 1;
    }
    .gallery-card-body {
        padding: 1.05rem 1.1rem 1.18rem;
        display: grid;
        gap: .46rem;
    }
    .gallery-title {
        margin: 0;
        font-size: 1.08rem;
        line-height: 1.35;
    }
    .gallery-caption {
        margin: 0;
        color: var(--text-secondary);
        font-size: .94rem;
        line-height: 1.7;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 980px) {
        .gallery-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .gallery-grid { grid-template-columns: 1fr; }
        .gallery-image { height: min(72vw, 320px); }
    }
    @media (prefers-reduced-motion: reduce) {
        .gallery-image,
        .gallery-media-wrap::after {
            transition: none;
        }
    }
</style>

<section class="page-section gallery-shell">
    <div class="page-hero gallery-hero">
        <div class="gallery-hero-copy">
            <p class="section-kicker">Gallery</p>
            <h1 class="section-title">Glow moments</h1>
            <p class="muted">A visual journey of Skin by Noor treatments, ambiance, and client care rituals.</p>
            <div class="gallery-support-row" aria-hidden="true">
                <span class="gallery-support-pill">Treatment Rituals</span>
                <span class="gallery-support-pill">Studio Ambience</span>
                <span class="gallery-support-pill">Signature Results</span>
            </div>
        </div>
    </div>

    @if($items->isEmpty())
        <div class="empty-state">Gallery images will be published soon.</div>
    @else
        <div class="gallery-grid">
            @foreach($items as $item)
                <article class="card gallery-card">
                    <div class="gallery-media-wrap">
                        <img
                            src="{{ $item->image_url ?: 'https://via.placeholder.com/600x400?text=Skin+by+Noor' }}"
                            alt="{{ $item->localized_title }}"
                            class="gallery-image"
                        >
                    </div>
                    <div class="gallery-card-body">
                        <h2 class="gallery-title">{{ $item->localized_title }}</h2>
                        <p class="gallery-caption">{{ $item->localized_caption }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
