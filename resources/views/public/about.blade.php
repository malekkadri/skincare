@extends('public.layouts.app')

@section('title', $about?->localized_title ?? 'About')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">About</p>
        <h1 class="section-title">{{ $about?->localized_title ?? 'Skin by Noor' }}</h1>
        <p class="muted">{{ $about?->localized_intro }}</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    <div class="grid grid-2">
        <article class="card">
            <h2>Our Story</h2>
            <p class="muted">{!! nl2br(e($about?->localized_story)) !!}</p>
        </article>
        <article class="card" style="background:var(--secondary);">
            <h2>Our Philosophy</h2>
            <p class="muted">{!! nl2br(e($about?->localized_philosophy)) !!}</p>
        </article>
    </div>
</section>
@endsection
