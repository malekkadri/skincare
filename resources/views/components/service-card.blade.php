@props(['service'])

<a href="{{ route('services.show', $service->slug) }}" class="service-card card">
    @if($service->image_url)
        <img src="{{ $service->image_url }}" alt="{{ $service->localized_name }}">
    @else
        <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" alt="Skincare treatment setup">
    @endif

    <div class="service-body">
        <h3>{{ $service->localized_name }}</h3>
        <p class="muted">{{ $service->localized_short_description ?: 'Tailored treatment for your skin needs.' }}</p>
        <p class="service-meta">{{ $service->display_price }} · {{ $service->duration_minutes }} min</p>
    </div>
</a>
