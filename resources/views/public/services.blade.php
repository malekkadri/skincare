@extends('public.layouts.app')

@section('title', 'Services')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Services</p>
        <h1 class="section-title">Signature skincare rituals</h1>
        <p class="muted">Treatments thoughtfully curated for visible results, comfort, and long-term skin health.</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    @forelse($categories as $category)
        <div style="margin-bottom:2.5rem;">
            <h2 style="margin-bottom:1rem;">{{ $category->localized_name }}</h2>
            <div class="grid grid-3">
                @forelse($category->services as $service)
                    <a class="card" href="{{ route('services.show', $service->slug) }}" style="display:block;">
                        <h3>{{ $service->localized_name }}</h3>
                        <p class="muted">{{ $service->localized_short_description }}</p>
                        <p style="color:#8b6e52;font-weight:600;">{{ $service->display_price }} • {{ $service->duration_minutes }} min</p>
                    </a>
                @empty
                    <div class="empty-state form-span-full">Services for this category will be published soon.</div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="empty-state">Services will be published soon.</div>
    @endforelse
</section>
@endsection
