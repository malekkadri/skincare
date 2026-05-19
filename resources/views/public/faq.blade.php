@extends('public.layouts.app')

@section('content')
@include('public.partials.page-hero', ['hero'=>$hero ?? null, 'fallbackTitle'=>__('public.nav.faq')])

@php
    use Illuminate\Support\Str;
@endphp
<style>
    .faq-page { display: grid; gap: clamp(1.75rem, 3vw, 2.7rem); }
    .faq-categories { display: grid; gap: 1.55rem; }
    .faq-category {
        padding: clamp(1rem, 2vw, 1.6rem);
        border-radius: 24px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, .6);
    }
    .faq-category-title {
        margin: 0 0 .9rem;
        font-size: clamp(1.15rem, 1.7vw, 1.35rem);
        letter-spacing: .01em;
    }
    .faq-list { display: grid; gap: .78rem; }
    .faq-item {
        padding: 0;
        overflow: hidden;
        border-radius: 18px;
    }
    .faq-item summary {
        list-style: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.2rem;
        font-weight: 600;
    }
    .faq-item summary::-webkit-details-marker { display: none; }
    .faq-toggle {
        width: 1.6rem;
        height: 1.6rem;
        border-radius: 999px;
        border: 1px solid var(--border-strong);
        display: inline-grid;
        place-items: center;
        color: var(--text-secondary);
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: transform .2s ease;
    }
    .faq-item[open] .faq-toggle { transform: rotate(45deg); }
    .faq-answer {
        margin: 0;
        padding: 0 1.2rem 1.05rem;
    }
    .faq-item summary:focus-visible {
        outline: 3px solid var(--focus-ring);
        outline-offset: -1px;
    }
</style>

<section class="page-section faq-page">
    <div class="page-hero">
        <p class="section-kicker">{{ __('public.faq_page.kicker') }}</p>
        <h1 class="section-title">{{ __('public.faq_page.title') }}</h1>
        <p class="muted">{{ __('public.faq_page.description') }}</p>
    </div>

    @if($items->isEmpty())
        <div class="empty-state">{{ __('public.faq_page.empty') }}</div>
    @else
        <div class="faq-categories">
            @foreach($items as $cat => $faqs)
            <section class="faq-category" aria-labelledby="faq-cat-{{ Str::slug($cat) }}">
                    <h2 id="faq-cat-{{ Str::slug($cat) }}" class="faq-category-title">{{ __('public.faq_page.categories.'.Str::slug($cat, '_')) }}</h2>
                    <div class="faq-list">
                        @foreach($faqs as $faq)
                            <details class="card faq-item">
                                <summary>
                                    <span>{{ $faq->localized_question }}</span>
                                    <span class="faq-toggle" aria-hidden="true">+</span>
                                </summary>
                                <p class="muted faq-answer">{{ $faq->localized_answer }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</section>
@endsection
