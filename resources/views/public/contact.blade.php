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
    .contact-actions {
        display: grid;
        gap: .65rem;
    }
    .contact-action {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
    }
    .contact-action-icon {
        width: 1.2rem;
        height: 1.2rem;
        stroke: currentColor;
        fill: none;
        flex: 0 0 auto;
    }
    @media (max-width: 900px) {
        .contact-layout { grid-template-columns: 1fr; }
        .map-frame { height: 320px; }
    }
</style>

<section class="page-section contact-page">
    @php
        $mapUrl = trim((string) $settings->map_embed_url);
        $isEmbeddableGoogleMap = filled($mapUrl) && str_contains($mapUrl, 'google.') && (str_contains($mapUrl, '/maps/embed') || str_contains($mapUrl, 'output=embed'));
    @endphp
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

            <div class="contact-actions" aria-label="{{ app()->getLocale() === 'fr' ? 'Contact et réseaux sociaux' : 'Follow and contact' }}">
                <div class="btn-row">
                    <a class="btn btn-soft contact-action" href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}"><svg class="contact-action-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 16.2v2.7a2 2 0 0 1-2.2 2 19.7 19.7 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.7 19.7 0 0 1 1 3.2 2 2 0 0 1 3 1h2.7a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L6.7 8.7a16 16 0 0 0 6.6 6.6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7A2 2 0 0 1 21 16.2z" stroke-width="1.8"></path></svg>{{ app()->getLocale() === 'fr' ? 'Appeler' : 'Call' }}</a>
                    @if(filled($settings->whatsapp_number))
                        <a class="btn btn-soft contact-action" target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}"><svg class="contact-action-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11.8c0 4.8-3.9 8.7-8.7 8.7-1.5 0-2.9-.4-4.1-1l-4.2 1.3 1.4-4c-.8-1.4-1.2-3-1.2-4.7C3.2 7.3 7.1 3.4 12 3.4s8 3.9 8 8.4Z" stroke-width="1.6"></path></svg>WhatsApp</a>
                    @endif
                </div>
                <div class="btn-row">
                    @if(filled($settings->instagram_url))
                        <a class="btn btn-soft contact-action" target="_blank" rel="noopener noreferrer" href="{{ $settings->instagram_url }}"><svg class="contact-action-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke-width="1.8"></rect><circle cx="12" cy="12" r="4.2" stroke-width="1.8"></circle><circle cx="17.3" cy="6.7" r="1"></circle></svg>Instagram</a>
                    @endif
                    @if(filled($settings->facebook_url))
                        <a class="btn btn-soft contact-action" target="_blank" rel="noopener noreferrer" href="{{ $settings->facebook_url }}"><svg class="contact-action-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V5h-3c-2.8 0-5 2.2-5 5v3H6v4h3v4h4v-4h3.2l.8-4H13v-2.8c0-.7.3-1.2 1-1.2Z" stroke-width="1.6"></path></svg>Facebook</a>
                    @endif
                    @if(filled($mapUrl))
                        <a class="btn btn-soft contact-action" href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Open Asthetika location on Google Maps"><svg class="contact-action-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z" stroke-width="1.8"></path><circle cx="12" cy="10" r="2.7" stroke-width="1.8"></circle></svg>{{ app()->getLocale() === 'fr' ? 'Itinéraire' : 'Directions' }}</a>
                    @endif
                </div>
            </div>

            <p class="contact-note">{{ app()->getLocale() === 'fr' ? 'Chaque demande est traitée avec attention. Les consultations et soins sont organisés sur rendez-vous.' : 'Every inquiry is handled with care. Consultations and treatments are organized by appointment.' }}</p>
        </article>

        <article class="card map-card" aria-label="Studio location map">
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
