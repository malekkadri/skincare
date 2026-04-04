@extends('public.layouts.app')

@section('title', $service->localized_name)

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Service Details</p>
        <h1 class="section-title">{{ $service->localized_name }}</h1>
        <p class="muted">{{ $service->localized_description }}</p>
        <div class="btn-row" style="margin-top:1.2rem;align-items:center;">
            <span style="font-weight:600;color:#8b6e52;">{{ $service->display_price }} · {{ $service->duration_minutes }} min</span>
            <a class="btn" href="{{ route('booking.service') }}">Book now</a>
        </div>
    </div>
</section>
@endsection
