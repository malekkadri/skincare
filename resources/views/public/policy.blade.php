@extends('public.layouts.app')

@section('title', $policy->localized_title)

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Policy</p>
        <h1 class="section-title">{{ $policy->localized_title }}</h1>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    <article class="card" style="max-width:920px;">
        <p class="muted" style="margin:0;">{!! nl2br(e($policy->localized_content)) !!}</p>
    </article>
</section>
@endsection
