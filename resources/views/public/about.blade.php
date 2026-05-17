@extends('public.layouts.app')

@section('title', $about?->localized_title ?? 'About')

@section('content')
@include('public.partials.page-hero', ['hero'=>$hero ?? null, 'fallbackTitle'=>__('public.nav.about')])

<style>
    .trust-page { display: grid; gap: clamp(1.8rem, 3vw, 2.8rem); }
    .trust-hero {
        display: grid;
        gap: 1.25rem;
    }
    .trust-hero-meta {
        display: inline-flex;
        width: fit-content;
        align-items: center;
        gap: .55rem;
        font-size: .74rem;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: var(--text-secondary);
        padding: .45rem .82rem;
        border-radius: 999px;
        border: 1px solid var(--border-strong);
        background: rgba(255, 255, 255, .74);
    }
    .trust-hero-meta::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--accent);
    }
    .editorial-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 1.1rem;
    }
    .story-card, .philosophy-card {
        padding: clamp(1.35rem, 2.6vw, 2.3rem);
    }
    .story-card p, .philosophy-card p { margin: 0; }
    .trust-divider {
        border-top: 1px solid rgba(218, 205, 191, .75);
        margin: .4rem 0 0;
    }
    .philosophy-card {
        background: linear-gradient(150deg, #f7eee5 0%, #fdfbf9 90%);
    }
    @media (max-width: 980px) {
        .editorial-grid { grid-template-columns: 1fr; }
    }
</style>

<section class="page-section trust-page">
    <div class="page-hero trust-hero">
        <span class="trust-hero-meta">Asthetika · Dr Aziz Sahly</span>
        <p class="section-kicker">{{ app()->getLocale() === 'fr' ? 'À propos' : 'About' }}</p>
        <h1 class="section-title">{{ $about?->localized_title ?? ($settings->site_name ?? 'Asthetika') }}</h1>
        <p class="muted">{{ $about?->localized_intro }}</p>
    </div>

    <div class="trust-divider" aria-hidden="true"></div>

    <div class="editorial-grid">
        <article class="card story-card">
            <p class="section-kicker">{{ app()->getLocale() === 'fr' ? 'Notre approche' : 'Our approach' }}</p>
            <h2>{{ app()->getLocale() === 'fr' ? 'Une prise en charge attentive et personnalisée' : 'Attentive and personalized care' }}</h2>
            <p class="muted">{!! nl2br(e($about?->localized_story)) !!}</p>
        </article>
        <article class="card philosophy-card">
            <p class="section-kicker">{{ app()->getLocale() === 'fr' ? 'Notre philosophie' : 'Our philosophy' }}</p>
            <h2>{{ app()->getLocale() === 'fr' ? 'Sublimer la peau avec naturel, précision et sécurité' : 'Enhancing the skin with natural-looking, precise, and safe care' }}</h2>
            <p class="muted">{!! nl2br(e($about?->localized_philosophy)) !!}</p>
        </article>
    </div>
</section>
@endsection
