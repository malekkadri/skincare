@extends('public.layouts.app')

@section('content')
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
        <p class="section-kicker">Testimonials</p>
        <h1 class="section-title">Loved by our clients</h1>
        <p class="muted">Words from clients who trust Skin by Noor for personalized skincare experiences.</p>
    </div>

    @if($items->isEmpty())
        <div class="empty-state">Client testimonials will appear here once published.</div>
    @else
        <div class="proof-grid" aria-label="Client testimonials">
            @foreach($items as $t)
                <article class="card proof-card">
                    <p class="proof-stars" aria-label="{{ $t->rating }} out of 5 stars">{{ str_repeat('★', $t->rating) }}</p>
                    <p class="proof-content">{{ $t->localized_content }}</p>
                    <div>
                        <p class="proof-client">{{ $t->client_name }}</p>
                        <span class="proof-badge">Client experience</span>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
