@extends('booking.layouts.app')

@section('content')
<div class="card">
    <h3 style="margin-bottom:1rem;">Choose your service</h3>
    <form method="POST" action="{{ route('booking.service.save') }}">
        @csrf
        <div class="grid grid-2">
            @forelse($services as $service)
                <label class="service-card">
                    <input type="radio" name="service_id" value="{{ $service->id }}" @checked(old('service_id', $wizard['service']?->id)===$service->id) style="width:auto;">
                    <strong>{{ $service->localized_name }}</strong><br>
                    <span style="color:#7A7A7A;">{{ $service->localized_short_description }}</span><br>
                    <small style="color:#8b6e52;font-weight:600;">{{ $service->display_price }} • {{ $service->duration_minutes }} min</small>
                </label>
            @empty
                <p>No services are currently available for booking.</p>
            @endforelse
        </div>
        <button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button>
    </form>
</div>
@endsection
