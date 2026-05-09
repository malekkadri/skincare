@extends('admin.layouts.app')

@section('title', 'Appointment Details')
@section('header', 'Appointment #'.$appointment->id)

@section('content')
<section class="card">
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem;">
        <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-secondary">Edit</a>
        <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="cancelled"><button class="btn btn-danger">Cancel</button></form>
        <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="completed"><button class="btn btn-success">Mark Completed</button></form>
        <form method="POST" action="{{ route('admin.appointments.resend-confirmation', $appointment) }}">@csrf<button class="btn">Resend Confirmation</button></form>
    </div>

    <div class="grid">
        <div><label>Patient</label><p>{{ $appointment->customer?->full_name }}<br>{{ $appointment->customer?->phone }}<br>{{ $appointment->customer?->email }}</p></div>
        <div><label>Service</label><p>{{ $appointment->service_name_snapshot_en }} / {{ $appointment->service_name_snapshot_fr }}</p></div>
        <div><label>Date & Time</label><p>{{ $appointment->appointment_date->format('Y-m-d') }} {{ \Illuminate\Support\Str::substr($appointment->start_time, 0, 5) }} - {{ \Illuminate\Support\Str::substr($appointment->end_time, 0, 5) }}</p></div>
        <div><label>Status</label><p><span class="status {{ $appointment->status_badge_class }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></p></div>
        <div><label>Booked Price</label><p>{{ $appointment->display_price }}</p></div>
        <div><label>Language / Currency</label><p>{{ strtoupper($appointment->preferred_language) }} / {{ $appointment->booked_currency }}</p></div>
        <div><label>Patient Notes</label><p>{{ $appointment->notes ?: '-' }}</p></div>
        <div><label>Admin Notes</label><p>{{ $appointment->admin_notes ?: '-' }}</p></div>
    </div>
</section>

<section class="card">
    <h2>WhatsApp History</h2>
    <table class="table">
        <thead><tr><th>Sent At</th><th>Template</th><th>Language</th><th>Recipient</th><th>Status</th><th>Message</th></tr></thead>
        <tbody>
        @forelse($appointment->whatsappLogs as $log)
            <tr>
                <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $log->template_key }}</td>
                <td>{{ strtoupper($log->language) }}</td>
                <td>{{ $log->recipient_phone }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->message_body ?: $log->provider_response }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No WhatsApp logs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
