@php($isEdit = $appointment->exists)
<form action="{{ $isEdit ? route('admin.appointments.update', $appointment) : route('admin.appointments.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <section class="card">
        <h2>Appointment Details</h2>
        <div class="grid">
            <div>
                <label>Service</label>
                <select name="service_id" required>
                    <option value="">Select service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id', $appointment->service_id)==$service->id)>{{ $service->name_en }} ({{ $service->duration_minutes }} min)</option>
                    @endforeach
                </select>
            </div>
            <div><label>Date</label><input type="date" name="appointment_date" value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}" required></div>
            <div><label>Start Time</label><input type="time" name="start_time" value="{{ old('start_time', $appointment->start_time ? \Illuminate\Support\Str::substr($appointment->start_time,0,5) : '') }}" required></div>
            <div>
                <label>Status</label>
                <select name="status" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $appointment->status ?: 'pending')===$status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Booked Currency</label>
                <select name="booked_currency" required>
                    <option value="TND" @selected(old('booked_currency', $appointment->booked_currency ?: 'TND')==='TND')>TND</option>
                    <option value="EUR" @selected(old('booked_currency', $appointment->booked_currency)==='EUR')>EUR</option>
                </select>
            </div>
            <div>
                <label>Select Existing Patient (optional)</label>
                <select name="customer_id">
                    <option value="">Create new patient</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected((int) old('customer_id', $appointment->customer_id)===$customer->id)>{{ $customer->full_name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Patient Note</label><textarea name="notes">{{ old('notes', $appointment->notes) }}</textarea></div>
            <div><label>Admin Note</label><textarea name="admin_notes">{{ old('admin_notes', $appointment->admin_notes) }}</textarea></div>
        </div>
    </section>

    <section class="card">
        <h2>New Patient (if not selected above)</h2>
        <div class="grid">
            <div><label>First Name</label><input type="text" name="customer[first_name]" value="{{ old('customer.first_name') }}"></div>
            <div><label>Last Name</label><input type="text" name="customer[last_name]" value="{{ old('customer.last_name') }}"></div>
            <div><label>Phone</label><input type="text" name="customer[phone]" value="{{ old('customer.phone') }}"></div>
            <div><label>Email</label><input type="email" name="customer[email]" value="{{ old('customer.email') }}"></div>
            <div>
                <label>Preferred Language</label>
                <select name="customer[preferred_language]">
                    <option value="fr" @selected(old('customer.preferred_language','fr')==='fr')>FR</option>
                    <option value="en" @selected(old('customer.preferred_language')==='en')>EN</option>
                </select>
            </div>
            <div>
                <label>Preferred Currency</label>
                <select name="customer[preferred_currency]">
                    <option value="TND" @selected(old('customer.preferred_currency','TND')==='TND')>TND</option>
                    <option value="EUR" @selected(old('customer.preferred_currency')==='EUR')>EUR</option>
                </select>
            </div>
            <div><label>Patient Notes</label><textarea name="customer[notes]">{{ old('customer.notes') }}</textarea></div>
        </div>
    </section>

    @if($errors->any())<section class="card"><div class="error">{{ $errors->first() }}</div></section>@endif
    <button class="btn" type="submit">{{ $isEdit ? 'Update' : 'Create' }} Appointment</button>
</form>
