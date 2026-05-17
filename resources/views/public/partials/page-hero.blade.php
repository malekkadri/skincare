@php
$title = $hero?->localized_title ?? $fallbackTitle ?? null;
$subtitle = $hero?->localized_subtitle ?? $fallbackSubtitle ?? null;
$description = $hero?->localized_description ?? $fallbackDescription ?? null;
$image = $hero?->image_url ?? 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1800&q=80';
$mobileImage = $hero?->mobile_image_url ?? $image;
$overlay = $hero?->overlay_opacity ?? 0.35;
@endphp
<section class="sbn-page-hero" style="--hero-overlay: {{ $overlay }};">
    <picture><source media="(max-width: 767px)" srcset="{{ $mobileImage }}"><img src="{{ $image }}" alt="{{ $hero?->localized_alt_text ?? $title ?? 'Asthetika' }}"></picture>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        @if($title)<h1>{{ $title }}</h1>@endif
        @if($subtitle)<p class="subtitle">{{ $subtitle }}</p>@endif
        @if($description)<p class="description">{{ $description }}</p>@endif
        @if(($hero?->localized_cta_label ?? null) && $hero?->cta_url)<a href="{{ $hero->cta_url }}" class="btn">{{ $hero->localized_cta_label }}</a>@endif
    </div>
</section>
