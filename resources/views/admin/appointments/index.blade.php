@extends('admin.layouts.app')

@section('title', 'Appointments')
@section('header', 'Appointments')

@section('content')
<section class="card">
    <form method="GET" class="grid">
        <div><label>Date</label><input type="date" name="date" value="{{ request('date') }}"></div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Service</label>
            <select name="service_id">
                <option value="">All</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @selected((int)request('service_id')===$service->id)>{{ $service->name_en }}</option>
                @endforeach
            </select>
        </div>
        <div style="align-self:end"><button class="btn" type="submit">Filter</button></div>
    </form>
</section>

<section class="card">
    <p><a href="{{ route('admin.appointments.create') }}" class="btn">New Appointment</a></p>
    <table class="table">
        <thead><tr><th>Date</th><th>Time</th><th>Customer</th><th>Service</th><th>Status</th><th>Price</th><th>Language</th><th>Currency</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($appointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment_date->format('Y-m-d') }}</td>
                <td>{{ \Illuminate\Support\Str::substr($appointment->start_time, 0, 5) }} - {{ \Illuminate\Support\Str::substr($appointment->end_time, 0, 5) }}</td>
                <td>{{ $appointment->customer?->full_name }}</td>
                <td>{{ $appointment->service_name_snapshot_en }}</td>
                <td><span class="muted">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></td>
                <td>{{ $appointment->display_price }}</td>
                <td>{{ strtoupper($appointment->customer?->preferred_language ?? '-') }}</td>
                <td>{{ $appointment->booked_currency }}</td>
                <td>
                    <a class="btn" href="{{ route('admin.appointments.show', $appointment) }}">View</a>
                    <a class="btn btn-secondary" href="{{ route('admin.appointments.edit', $appointment) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}" style="display:inline-block">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button class="btn btn-danger" type="submit">Cancel</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9">No appointments found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $appointments->links() }}
</section>
@endsection
