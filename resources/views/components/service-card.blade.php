@props(['service'])

<a href="{{ route('services.show', $service->slug) }}" class="service-card card">
    @if($service->image_url)
        <img src="{{ $service->image_url }}" alt="{{ $service->localized_name }}">
    @else
        <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" alt="{{ __('public.common.service_fallback') }}">
    @endif

    <div class="service-body">
        <h3>{{ $service->localized_name }}</h3>
        <p class="muted">{{ $service->localized_short_description ?: __('public.home.services_title') }}</p>
        <p class="service-meta">{{ $service->display_price }} · {{ $service->duration_minutes }} {{ __('public.common.minutes') }}</p>
    </div>
</a>
