@extends('booking.layouts.app')

@section('content')
<div class="card" style="max-width:720px;">
    <h3>{{ __('booking.success') }}</h3>
    <p>#{{ $appointment->id }} — {{ $appointment->appointment_date->format('Y-m-d') }} {{ \Illuminate\Support\Str::substr($appointment->start_time, 0, 5) }}</p>
    <p>{{ $appointment->service_name_snapshot_en }}</p>
    <a class="btn" href="{{ route('booking.service') }}">New booking</a>
</div>
@endsection
