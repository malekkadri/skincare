@extends('booking.layouts.app')

@section('content')
<div class="card" style="max-width:640px;">
    <h3 class="title">Select a date</h3>
    <p class="subtitle">Choose a day first and we’ll instantly check how many time slots are available.</p>
    <form method="POST" action="{{ route('booking.date.save') }}">
        @csrf
        <label>Date</label>
        <input
            type="date"
            id="appointment_date"
            name="appointment_date"
            min="{{ now()->toDateString() }}"
            value="{{ old('appointment_date', $wizard['appointment_date']) }}"
            required
        >
        @error('appointment_date')
            <p class="field-error">{{ $message }}</p>
        @enderror
        <div id="availability_hint" class="subtitle" style="margin-top:.8rem;margin-bottom:0;"></div>
        <button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button>
    </form>
</div>
<script>
(() => {
    const dateInput = document.getElementById('appointment_date');
    const hint = document.getElementById('availability_hint');
    const serviceId = @json($wizard['service']?->id);
    const endpoint = @json(route('booking.available-slots'));

    const updateHint = async () => {
        if (!dateInput.value || !serviceId) {
            hint.textContent = '';
            return;
        }

        hint.textContent = 'Checking available time slots...';

        try {
            const query = new URLSearchParams({ service_id: String(serviceId), date: dateInput.value });
            const response = await fetch(`${endpoint}?${query.toString()}`, { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            const slots = Array.isArray(payload.slots) ? payload.slots : [];

            hint.textContent = slots.length
                ? `${slots.length} slot${slots.length > 1 ? 's' : ''} available on this day.`
                : 'No slots available on this day. Try another date.';
        } catch (error) {
            hint.textContent = 'Unable to check slots right now. You can still continue and try the next step.';
        }
    };

    dateInput.addEventListener('change', updateHint);
    if (dateInput.value) {
        updateHint();
    }
})();
</script>
@endsection
