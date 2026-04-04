@extends('admin.layouts.app')
@section('title', 'AI Content Helper')
@section('header', 'AI Content Helper')
@section('content')
@if(! $settings->ai_enabled)
<div class="card">
    <p class="muted">AI is currently disabled. You can still access this page, but generation will be skipped until AI is enabled.</p>
</div>
@endif

<form method="POST" action="{{ route('admin.ai-content-helper.generate') }}" class="card">@csrf
    <div class="grid">
        <div><label>Content type</label><select name="content_type">
            @foreach(['service_description','homepage_copy','faq_answer','promotion','testimonial_title','gallery_caption'] as $type)
                <option value="{{ $type }}" @selected(($input['content_type'] ?? '') === $type)>{{ str_replace('_',' ',$type) }}</option>
            @endforeach
        </select></div>
        <div><label>Language</label><select name="language"><option value="fr" @selected(($input['language'] ?? '')==='fr')>FR</option><option value="en" @selected(($input['language'] ?? '')==='en')>EN</option></select></div>
        <div><label>Context (optional)</label><textarea name="context">{{ $input['context'] ?? '' }}</textarea></div>
        <div><label>Prompt</label><textarea name="prompt" required>{{ $input['prompt'] ?? '' }}</textarea></div>
    </div>
    <button class="btn" type="submit">Generate Draft</button>
</form>

@if($result)
<div class="card">
    <h2>Generated Draft</h2>
    <p><strong>Status:</strong> {{ $result['status'] }}</p>
    @if(($result['status'] ?? '') === 'success')
        @if(!empty($result['title']))<p><strong>Title:</strong> {{ $result['title'] }}</p>@endif
        <p><strong>Body:</strong><br>{{ $result['body'] ?? '' }}</p>
        @if(!empty($result['cta']))<p><strong>CTA:</strong> {{ $result['cta'] }}</p>@endif
    @else
        <p class="error">{{ $result['error_message'] ?? 'AI unavailable' }}</p>
    @endif
</div>
@endif
@endsection
