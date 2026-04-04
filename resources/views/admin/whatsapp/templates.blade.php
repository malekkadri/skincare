@extends('admin.layouts.app')

@section('title', 'WhatsApp Templates')
@section('header', 'WhatsApp Templates')

@section('content')
<section class="card">
    <p class="muted">Placeholders: {client_name}, {service_name}, {appointment_date}, {appointment_time}, {business_name}, {whatsapp_number}</p>
    <table class="table">
        <thead><tr><th>Key</th><th>Lang</th><th>Message</th><th>Active</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($templates as $template)
            <tr>
                <td>{{ $template->key }}</td>
                <td>{{ strtoupper($template->language) }}</td>
                <td style="width:50%">
                    <form action="{{ route('admin.whatsapp.templates.update', $template) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="key" value="{{ $template->key }}">
                        <input type="hidden" name="language" value="{{ $template->language }}">
                        <textarea name="message_body" rows="3">{{ old('message_body', $template->message_body) }}</textarea>
                </td>
                <td><input type="checkbox" name="is_active" value="1" @checked($template->is_active)></td>
                <td><button class="btn">Save</button></form></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
