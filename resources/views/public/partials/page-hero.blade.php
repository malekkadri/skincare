@php
$title = $hero?->localized_title ?? $fallbackTitle ?? null;
$subtitle = $hero?->localized_subtitle ?? $fallbackSubtitle ?? null;
$description = $hero?->localized_description ?? $fallbackDescription ?? null;
$image = $hero?->image_url;
$mobileImage = $hero?->mobile_image_url ?? $image;
$overlay = $hero?->overlay_opacity ?? 0.35;
@endphp
<section class="sbn-page-hero" style="--hero-overlay: {{ $overlay }};">
    @if($image)
        <picture><source media="(max-width: 767px)" srcset="{{ $mobileImage }}"><img src="{{ $image }}" alt="{{ $hero?->localized_alt_text ?? $title ?? 'Asthetika' }}"></picture>
    @else
        <div class="hero-image-fallback" aria-hidden="true"></div>
    @endif
    <div class="hero-overlay"></div>
    <div class="hero-content">
        @if($title)<h1>{{ $title }}</h1>@endif
        @if($subtitle)<p class="subtitle">{{ $subtitle }}</p>@endif
        @if($description)<p class="description">{{ $description }}</p>@endif
        @if(($hero?->localized_cta_label ?? null) && $hero?->cta_url)<a href="{{ $hero->cta_url }}" class="btn">{{ $hero->localized_cta_label }}</a>@endif
    </div>
</section>
