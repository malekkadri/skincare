@extends('admin.layouts.app')

@section('title', 'WhatsApp Settings')
@section('header', 'WhatsApp Settings')

@section('content')
<form action="{{ route('admin.whatsapp.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    <section class="card">
        <h2>Provider</h2>
        <div class="grid">
            <div><label><input type="checkbox" name="whatsapp_enabled" value="1" @checked(old('whatsapp_enabled', $settings->whatsapp_enabled))> WhatsApp Enabled</label></div>
            <div><label>Provider</label><input type="text" name="whatsapp_provider" value="{{ old('whatsapp_provider', $settings->whatsapp_provider) }}" placeholder="log / twilio / meta"></div>
            <div><label>Business Number</label><input type="text" name="whatsapp_business_number" value="{{ old('whatsapp_business_number', $settings->whatsapp_business_number) }}"></div>
            <div><label>API Base URL</label><input type="url" name="whatsapp_api_base_url" value="{{ old('whatsapp_api_base_url', $settings->whatsapp_api_base_url) }}"></div>
            <div><label>API Key/Token</label><input type="password" name="whatsapp_api_key" placeholder="Leave blank to keep current"></div>
            <div><label>Default Country Code</label><input type="text" name="whatsapp_default_country_code" value="{{ old('whatsapp_default_country_code', $settings->whatsapp_default_country_code) }}"></div>
        </div>
    </section>

    <section class="card">
        <h2>Manual Event Toggles</h2>
        <div class="grid">
            <div><label><input type="checkbox" name="send_booking_confirmation_whatsapp" value="1" @checked(old('send_booking_confirmation_whatsapp', $settings->send_booking_confirmation_whatsapp))> Booking confirmation</label></div>
            <div><label><input type="checkbox" name="send_booking_cancellation_whatsapp" value="1" @checked(old('send_booking_cancellation_whatsapp', $settings->send_booking_cancellation_whatsapp))> Booking cancellation</label></div>
            <div><label><input type="checkbox" name="send_booking_reschedule_whatsapp" value="1" @checked(old('send_booking_reschedule_whatsapp', $settings->send_booking_reschedule_whatsapp))> Booking reschedule</label></div>
        </div>
    </section>

    <section class="card">
        <h2>Automation Controls</h2>
        <div class="grid">
            <div><label><input type="checkbox" name="whatsapp_automation_enabled" value="1" @checked(old('whatsapp_automation_enabled', $settings->whatsapp_automation_enabled))> Enable WhatsApp automation</label></div>
            <div><label>Pause automation until (Africa/Tunis)</label><input type="datetime-local" name="automation_pause_until" value="{{ old('automation_pause_until', optional(optional($settings->automation_pause_until)->timezone('Africa/Tunis'))->format('Y-m-d\TH:i')) }}"></div>
            <div><label><input type="checkbox" name="send_24h_reminder" value="1" @checked(old('send_24h_reminder', $settings->send_24h_reminder))> Send 24-hour reminder</label></div>
            <div><label>24h reminder lead (minutes)</label><input type="number" min="5" max="10080" name="reminder_24h_lead_minutes" value="{{ old('reminder_24h_lead_minutes', $settings->reminder_24h_lead_minutes) }}"></div>
            <div><label><input type="checkbox" name="send_2h_reminder" value="1" @checked(old('send_2h_reminder', $settings->send_2h_reminder))> Send 2-hour reminder</label></div>
            <div><label>2h reminder lead (minutes)</label><input type="number" min="5" max="1440" name="reminder_2h_lead_minutes" value="{{ old('reminder_2h_lead_minutes', $settings->reminder_2h_lead_minutes) }}"></div>
            <div><label><input type="checkbox" name="send_post_appointment_followup" value="1" @checked(old('send_post_appointment_followup', $settings->send_post_appointment_followup))> Send post-appointment follow-up</label></div>
            <div><label>Follow-up lead (minutes after appointment start)</label><input type="number" min="5" max="20160" name="followup_lead_minutes" value="{{ old('followup_lead_minutes', $settings->followup_lead_minutes) }}"></div>
            <div><label><input type="checkbox" name="send_consultation_acknowledgement" value="1" @checked(old('send_consultation_acknowledgement', $settings->send_consultation_acknowledgement))> Send consultation acknowledgement</label></div>
            <div><label>Max retry attempts</label><input type="number" min="1" max="10" name="max_whatsapp_retry_attempts" value="{{ old('max_whatsapp_retry_attempts', $settings->max_whatsapp_retry_attempts) }}"></div>
            <div><label>Retry backoff (minutes)</label><input type="number" min="1" max="1440" name="whatsapp_retry_backoff_minutes" value="{{ old('whatsapp_retry_backoff_minutes', $settings->whatsapp_retry_backoff_minutes) }}"></div>
        </div>
    </section>

    <button class="btn">Save WhatsApp Settings</button>
</form>
@endsection
