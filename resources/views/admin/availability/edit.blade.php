@extends('admin.layouts.app')

@section('title', 'Availability')
@section('header', 'Availability')

@section('content')
<form action="{{ route('admin.availability.hours.update') }}" method="POST" class="card">
    @csrf
    @method('PUT')
    <h2>Weekly Business Hours</h2>
    <table class="table">
        <thead><tr><th>Day</th><th>Open</th><th>Start</th><th>End</th></tr></thead>
        <tbody>
        @foreach($dayNames as $dayIndex => $dayName)
            @php($dayHour = $hours[$dayIndex] ?? null)
            <tr>
                <td>{{ $dayName }}</td>
                <td><input type="checkbox" name="hours[{{ $dayIndex }}][is_open]" value="1" @checked(old("hours.$dayIndex.is_open", $dayHour?->is_open))></td>
                <td><input type="time" name="hours[{{ $dayIndex }}][start_time]" value="{{ old("hours.$dayIndex.start_time", $dayHour?->start_time ? \Illuminate\Support\Str::substr($dayHour->start_time,0,5) : '') }}"></td>
                <td><input type="time" name="hours[{{ $dayIndex }}][end_time]" value="{{ old("hours.$dayIndex.end_time", $dayHour?->end_time ? \Illuminate\Support\Str::substr($dayHour->end_time,0,5) : '') }}"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <button class="btn" type="submit">Save Weekly Hours</button>
</form>

<form action="{{ route('admin.availability.settings.update') }}" method="POST" class="card">
    @csrf
    @method('PUT')
    <h2>Booking Settings</h2>
    <div class="grid">
        <div><label>Slot Interval Minutes</label><input type="number" name="slot_interval_minutes" min="5" value="{{ old('slot_interval_minutes', $settings->slot_interval_minutes) }}"></div>
        <div><label>Minimum Notice Hours</label><input type="number" name="minimum_notice_hours" min="0" value="{{ old('minimum_notice_hours', $settings->minimum_notice_hours) }}"></div>
        <div><label>Maximum Booking Days Ahead</label><input type="number" name="maximum_booking_days_ahead" min="1" value="{{ old('maximum_booking_days_ahead', $settings->maximum_booking_days_ahead) }}"></div>
        <div><label>Max Appointments Per Day</label><input type="number" name="max_appointments_per_day" min="1" value="{{ old('max_appointments_per_day', $settings->max_appointments_per_day) }}"></div>
        <div><label><input type="checkbox" name="booking_enabled" value="1" @checked(old('booking_enabled', $settings->booking_enabled))> Booking Enabled</label></div>
    </div>
    <button class="btn" type="submit">Save Booking Settings</button>
</form>
@endsection
