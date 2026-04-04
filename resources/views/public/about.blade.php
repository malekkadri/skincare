@extends('public.layouts.app')
@section('title', $about?->localized_title ?? 'About')
@section('content')<section class="section"><h1>{{ $about?->localized_title }}</h1><p>{{ $about?->localized_intro }}</p><div class="grid grid-2"><div class="card">{!! nl2br(e($about?->localized_story)) !!}</div><div class="card">{!! nl2br(e($about?->localized_philosophy)) !!}</div></div></section>@endsection
