@extends('public.layouts.app')

@section('content')
@include('public.partials.page-hero', ['hero'=>$hero ?? null, 'fallbackTitle'=>__('public.nav.testimonials')])

<style>
    .proof-page { display: grid; gap: clamp(1.75rem, 3vw, 2.75rem); }
    .proof-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.1rem;
    }
    .proof-card {
        padding: clamp(1.25rem, 2.2vw, 1.95rem);
        display: grid;
        gap: .95rem;
        position: relative;
        overflow: hidden;
    }
    .proof-card::before {
        content: "“";
        position: absolute;
        right: 1.05rem;
        top: .6rem;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 3.5rem;
        color: rgba(185, 144, 104, .17);
        line-height: 1;
    }
    .proof-stars {
        color: #b07d4f;
        letter-spacing: .18em;
        font-size: .95rem;
        margin: 0;
        font-weight: 700;
    }
    .proof-content {
        margin: 0;
        color: var(--text-primary);
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    .proof-client {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: .02em;
    }
    .proof-badge {
        font-size: .72rem;
        letter-spacing: .11em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }
    @media (max-width: 980px) {
        .proof-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 680px) {
        .proof-grid { grid-template-columns: 1fr; }
    }
</style>

<section class="page-section proof-page">
    <div class="page-hero">
        <p class="section-kicker">{{ __('public.testimonials_page.kicker') }}</p>
        <h1 class="section-title">{{ __('public.testimonials_page.title') }}</h1>
        <p class="muted">{{ __('public.testimonials_page.description') }}</p>
    </div>

    @if($items->isEmpty())
        <div class="empty-state">{{ __('public.testimonials_page.empty') }}</div>
    @else
        <div class="proof-grid" aria-label="{{ __('public.testimonials_page.grid_label') }}">
            @foreach($items as $t)
                <article class="card proof-card">
                    <p class="proof-stars" aria-label="{{ __('public.testimonials_page.rating_aria', ['rating' => $t->rating]) }}">{{ str_repeat('★', $t->rating) }}</p>
                    <p class="proof-content">{{ $t->localized_content }}</p>
                    @if($t->localized_title)
                        <p class="proof-badge">{{ $t->localized_title }}</p>
                    @endif
                    <div>
                        <p class="proof-client">{{ $t->client_name }}</p>
                        <span class="proof-badge">{{ __('public.testimonials_page.client_experience') }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
