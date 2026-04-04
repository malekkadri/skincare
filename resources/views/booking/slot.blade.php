@extends('booking.layouts.app')
@section('content')
<div class="card"><form method="POST" action="{{ route('booking.slot.save') }}">@csrf
<div class="grid grid-2">
@forelse($slots as $slot)
<label class="service-card"><input type="radio" name="start_time" value="{{ $slot }}" @checked(old('start_time', $wizard['start_time'])===$slot)> {{ $slot }}</label>
@empty
<p>{{ __('booking.slot_unavailable') }}</p>
@endforelse
</div>
<button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button></form></div>
@endsection
