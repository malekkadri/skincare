@extends('admin.layouts.app')

@section('title', 'WhatsApp Log #'.$log->id)
@section('header', 'WhatsApp Log Details')

@section('content')
<div class="card">
    <h2>Message #{{ $log->id }}</h2>
    <p><strong>Status:</strong> {{ $log->status }}</p>
    <p><strong>Template:</strong> {{ $log->template_key }}</p>
    <p><strong>Source:</strong> {{ $log->automation_source ?? '-' }}</p>
    <p><strong>Language:</strong> {{ strtoupper($log->language) }}</p>
    <p><strong>Recipient:</strong> {{ $log->recipient_phone ?? '-' }}</p>
    <p><strong>Appointment ID:</strong> {{ $log->appointment_id ?? '-' }}</p>
    <p><strong>Consultation ID:</strong> {{ $log->related_consultation_id ?? '-' }}</p>
    <p><strong>Attempts:</strong> {{ $log->attempts }}</p>
    <p><strong>Scheduled For:</strong> {{ $log->scheduled_for?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? '-' }}</p>
    <p><strong>Sent At:</strong> {{ $log->sent_at?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? '-' }}</p>
    <p><strong>Failed At:</strong> {{ $log->failed_at?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? '-' }}</p>
    <p><strong>Error Code:</strong> {{ $log->error_code ?? '-' }}</p>
    <p><strong>Body:</strong><br>{{ $log->message_body ?? '-' }}</p>
    <p><strong>Provider Response:</strong><br>{{ $log->provider_response ?? '-' }}</p>

    @if($log->status === 'failed')
        <form action="{{ route('admin.whatsapp.logs.retry', $log) }}" method="POST">
            @csrf
            <button class="btn btn-success" type="submit">Retry this message</button>
        </form>
    @endif
</div>
@endsection
