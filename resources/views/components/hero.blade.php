@props([
    'title',
    'description',
    'image',
    'ctaUrl',
    'ctaLabel' => 'Book Now',
])

<section class="page-section hero-grid" aria-label="Hero">
    <div class="hero-image-wrap card">
        <img src="{{ $image }}" alt="Luxury skincare treatment">
    </div>
    <div>
        <p class="section-kicker">Premium Skincare Studio</p>
        <h1 class="hero-title">{{ $title }}</h1>
        <p class="muted">{{ $description }}</p>
        <div style="margin-top:1.8rem">
            <a href="{{ $ctaUrl }}" class="btn">{{ $ctaLabel }}</a>
        </div>
    </div>
</section>
