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
        <h2>Event Toggles</h2>
        <div class="grid">
            <div><label><input type="checkbox" name="send_booking_confirmation_whatsapp" value="1" @checked(old('send_booking_confirmation_whatsapp', $settings->send_booking_confirmation_whatsapp))> Booking confirmation</label></div>
            <div><label><input type="checkbox" name="send_booking_cancellation_whatsapp" value="1" @checked(old('send_booking_cancellation_whatsapp', $settings->send_booking_cancellation_whatsapp))> Booking cancellation</label></div>
            <div><label><input type="checkbox" name="send_booking_reschedule_whatsapp" value="1" @checked(old('send_booking_reschedule_whatsapp', $settings->send_booking_reschedule_whatsapp))> Booking reschedule</label></div>
        </div>
    </section>

    <button class="btn">Save WhatsApp Settings</button>
</form>
@endsection
