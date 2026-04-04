@extends('booking.layouts.app')
@section('content')
<div class="card"><form method="POST" action="{{ route('booking.service.save') }}">@csrf
<div class="grid grid-2">
@foreach($services as $service)
<label class="service-card"><input type="radio" name="service_id" value="{{ $service->id }}" @checked(old('service_id', $wizard['service']?->id)===$service->id)> <strong>{{ $service->localized_name }}</strong><br>{{ $service->localized_short_description }}<br><small>{{ $service->display_price }} • {{ $service->duration_minutes }} min</small></label>
@endforeach
</div><button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button></form></div>
@endsection
