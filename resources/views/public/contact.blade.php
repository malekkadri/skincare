@extends('public.layouts.app')

@section('title', $settings->localized('contact_page_title') ?? 'Contact')

@section('content')
@include('public.partials.page-hero', ['hero'=>$hero ?? null, 'fallbackTitle'=>__('public.nav.contact')])

<style>
    .contact-page { display: grid; gap: clamp(1.8rem, 3vw, 2.8rem); }
    .contact-layout {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 1.15rem;
    }
    .contact-card {
        padding: clamp(1.3rem, 2.7vw, 2.1rem);
        display: grid;
        gap: 1rem;
    }
    .contact-line {
        display: grid;
        gap: .25rem;
        padding-bottom: .8rem;
        border-bottom: 1px solid rgba(218, 205, 191, .65);
    }
    .contact-line:last-of-type {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .contact-label {
        font-size: .73rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--text-secondary);
        font-weight: 600;
    }
    .contact-value {
        margin: 0;
        font-size: 1.02rem;
        line-height: 1.65;
    }
    .contact-note {
        border-radius: 14px;
        background: rgba(242, 234, 225, .75);
        border: 1px solid var(--border);
        padding: .85rem 1rem;
        font-size: .93rem;
        color: var(--text-secondary);
        margin: 0;
    }
    .map-card {
        overflow: hidden;
        padding: 0;
        border-radius: 24px;
    }
    .map-frame {
        width: 100%;
        min-height: 100%;
        height: 390px;
        border: 0;
        display: block;
    }
    @media (max-width: 900px) {
        .contact-layout { grid-template-columns: 1fr; }
        .map-frame { height: 320px; }
    }
</style>

<section class="page-section contact-page">
    <div class="page-hero">
        <p class="section-kicker">Contact</p>
        <h1 class="section-title">{{ $settings->localized('contact_page_title') ?? 'Get in touch' }}</h1>
        <p class="muted">{{ $settings->localized('contact_intro') }}</p>
    </div>

    <div class="contact-layout">
        <article class="card contact-card" aria-label="Contact details">
            <h2>{{ app()->getLocale() === 'fr' ? 'Visitez ou contactez Asthetika' : 'Visit or contact Asthetika' }}</h2>

            <div class="contact-line">
                <span class="contact-label">Address</span>
                <p class="contact-value muted">{{ $settings->localized('address') }}</p>
            </div>

            <div class="contact-line">
                <span class="contact-label">Phone</span>
                <p class="contact-value"><a href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}">{{ $settings->phone }}</a></p>
            </div>

            @if(filled($settings->whatsapp_number))
                <div class="contact-line">
                    <span class="contact-label">WhatsApp</span>
                    <p class="contact-value">{{ $settings->whatsapp_number }}</p>
                </div>
                <div class="btn-row">
                    <a class="btn" target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}">Message on WhatsApp</a>
                </div>
            @endif

            <p class="contact-note">{{ app()->getLocale() === 'fr' ? 'Chaque demande est traitée avec attention. Les consultations et soins sont organisés sur rendez-vous.' : 'Every inquiry is handled with care. Consultations and treatments are organized by appointment.' }}</p>
        </article>

        <article class="card map-card" aria-label="Studio location map">
            @php
                $mapUrl = trim((string) $settings->map_embed_url);
                $isEmbeddableGoogleMap = filled($mapUrl) && str_contains($mapUrl, 'google.') && (str_contains($mapUrl, '/maps/embed') || str_contains($mapUrl, 'output=embed'));
            @endphp

            @if($isEmbeddableGoogleMap)
                <iframe class="map-frame" src="{{ $settings->map_embed_url }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            @elseif(filled($mapUrl))
                <div class="empty-state" style="height:100%;display:grid;place-items:center;padding:2rem;">
                    <a
                        class="btn btn-soft"
                        href="{{ $mapUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Open Asthetika location on Google Maps"
                    >
                        {{ app()->getLocale() === 'fr' ? 'Ouvrir l’adresse sur Google Maps' : 'Open location on Google Maps' }}
                    </a>
                </div>
            @else
                <div class="empty-state" style="height:100%;display:grid;place-items:center;padding:2rem;">Map details will be available soon.</div>
            @endif
        </article>
    </div>
</section>
@endsection
