@extends('admin.layouts.app')
@section('title', 'AI Settings')
@section('header', 'AI Settings')
@section('content')
<form method="POST" action="{{ route('admin.ai-settings.update') }}" class="card">@csrf @method('PUT')
    <h2>Provider & Access</h2>
    <div class="grid">
        <div><label><input type="checkbox" name="ai_enabled" value="1" {{ old('ai_enabled', $settings->ai_enabled) ? 'checked' : '' }}> Enable AI</label></div>
        <div>
            <label>Provider</label>
            <input name="ai_provider" value="groq" readonly>
            <small class="muted">Text generation provider is fixed to Groq.</small>
            @error('ai_provider')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div><label>API Key</label><input type="password" name="ai_api_key" value="{{ old('ai_api_key', $settings->ai_api_key) }}" autocomplete="off">@error('ai_api_key')<div class="error">{{ $message }}</div>@enderror</div>
        <div><label>Model</label><input name="ai_model" value="{{ old('ai_model', $settings->ai_model ?? 'llama-3.3-70b-versatile') }}"></div>
        <div><label>Base URL</label><input name="ai_base_url" value="{{ old('ai_base_url', $settings->ai_base_url ?? 'https://api.groq.com/openai/v1/chat/completions') }}"></div>
        <div><label>Temperature</label><input type="number" step="0.05" min="0" max="1" name="ai_temperature" value="{{ old('ai_temperature', $settings->ai_temperature) }}"></div>
        <div><label>Timeout (seconds)</label><input type="number" min="5" max="120" name="ai_timeout_seconds" value="{{ old('ai_timeout_seconds', $settings->ai_timeout_seconds) }}"></div>
    </div>

    <h2 style="margin-top:1rem">Feature Flags</h2>
    <div class="grid">
        <label><input type="checkbox" name="ai_enable_consultation_summary" value="1" {{ old('ai_enable_consultation_summary', $settings->ai_enable_consultation_summary) ? 'checked' : '' }}> Consultation summaries</label>
        <label><input type="checkbox" name="ai_enable_service_recommendation" value="1" {{ old('ai_enable_service_recommendation', $settings->ai_enable_service_recommendation) ? 'checked' : '' }}> Service recommendations</label>
        <label><input type="checkbox" name="ai_enable_admin_content_helper" value="1" {{ old('ai_enable_admin_content_helper', $settings->ai_enable_admin_content_helper) ? 'checked' : '' }}> Admin content helper</label>
    </div>

    <p class="muted" style="margin-top:1rem">Use AI as a drafting assistant only. No medical diagnosis or guaranteed outcomes.</p>
    <button class="btn" type="submit">Save AI Settings</button>
</form>
@endsection
