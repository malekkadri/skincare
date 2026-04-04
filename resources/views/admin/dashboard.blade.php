@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="grid">
        <div class="card"><h2>Pending WhatsApp</h2><p>{{ $pendingMessages }}</p></div>
        <div class="card"><h2>Failed WhatsApp</h2><p>{{ $failedMessages }}</p></div>
        <div class="card"><h2>Queued Today</h2><p>{{ $queuedToday }}</p></div>
        <div class="card"><h2>Sent Today</h2><p>{{ $sentToday }}</p></div>
        <div class="card"><h2>Failed Today</h2><p>{{ $failedToday }}</p></div>
        <div class="card">
            <h2>Automation Status</h2>
            <p>{{ $settings->whatsapp_automation_enabled ? 'Enabled' : 'Disabled' }}</p>
            <p class="muted">Pause until: {{ $settings->automation_pause_until?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? 'Not paused' }}</p>
        </div>
    </div>
@endsection
