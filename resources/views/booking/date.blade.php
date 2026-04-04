@extends('booking.layouts.app')

@section('content')
<div class="card" style="max-width:640px;">
    <h3>Select a date</h3>
    <form method="POST" action="{{ route('booking.date.save') }}">
        @csrf
        <label>Date</label>
        <input type="date" name="appointment_date" value="{{ old('appointment_date', $wizard['appointment_date']) }}" required>
        <button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button>
    </form>
</div>
@endsection
