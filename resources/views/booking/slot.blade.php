@extends('booking.layouts.app')

@section('content')
<div class="card">
    <h3 class="title">Select a time slot</h3>
    <p class="subtitle">Date selected: <strong>{{ $wizard['appointment_date'] }}</strong></p>
    <form method="POST" action="{{ route('booking.slot.save') }}">
        @csrf
        <div class="grid grid-2">
            @forelse($slots as $slot)
                <label class="service-card" style="display:flex;align-items:center;gap:.6rem;cursor:pointer;">
                    <input type="radio" name="start_time" value="{{ $slot }}" @checked(old('start_time', $wizard['start_time'])===$slot) style="width:auto;">
                    <strong>{{ $slot }}</strong>
                </label>
            @empty
                <div class="notice" style="margin:0;">
                    {{ __('booking.slot_unavailable') }}
                    <div style="margin-top:.6rem;">
                        <a href="{{ route('booking.date') }}" class="btn btn-soft">Pick another date</a>
                    </div>
                </div>
            @endforelse
        </div>
        @error('start_time')
            <p class="field-error">{{ $message }}</p>
        @enderror
        <button id="slot_submit" class="btn" style="margin-top:1rem" @disabled(count($slots) === 0 || !old('start_time', $wizard['start_time']))>{{ __('booking.continue') }}</button>
    </form>
</div>
<script>
(() => {
    const button = document.getElementById('slot_submit');
    const radios = [...document.querySelectorAll('input[name="start_time"]')];
    if (!button || radios.length === 0) return;

    const syncButton = () => {
        button.disabled = !radios.some(radio => radio.checked);
    };

    radios.forEach(radio => radio.addEventListener('change', syncButton));
    syncButton();
})();
</script>
@endsection
