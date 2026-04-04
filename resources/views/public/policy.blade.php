@extends('public.layouts.app')
@section('title', $policy->localized_title)
@section('content')<section class="section"><h1>{{ $policy->localized_title }}</h1><div class="card">{!! nl2br(e($policy->localized_content)) !!}</div></section>@endsection
