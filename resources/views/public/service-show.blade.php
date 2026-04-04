@extends('public.layouts.app')
@section('title', $service->localized_name)
@section('content')<section class="section"><h1>{{ $service->localized_name }}</h1><p>{{ $service->localized_description }}</p><p><strong>{{ $service->display_price }}</strong> · {{ $service->duration_minutes }} min</p><a class="btn" href="{{ route('booking.service') }}">Book now</a></section>@endsection
