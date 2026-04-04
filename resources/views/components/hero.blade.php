@props([
    'title',
    'description',
    'image',
    'ctaUrl',
    'ctaLabel' => __('public.nav.book_now'),
])

<section class="page-section hero-grid" aria-label="Hero">
    <div class="hero-image-wrap card">
        <img src="{{ $image }}" alt="{{ __('public.home.cta_title') }}">
    </div>
    <div>
        <p class="section-kicker">{{ __('public.home.hero_kicker') }}</p>
        <h1 class="hero-title">{{ $title }}</h1>
        <p class="muted">{{ $description }}</p>
        <div style="margin-top:1.8rem">
            <a href="{{ $ctaUrl }}" class="btn">{{ $ctaLabel }}</a>
        </div>
    </div>
</section>
