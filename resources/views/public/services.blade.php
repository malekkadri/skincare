@extends('public.layouts.app')
@section('title', 'Services')
@section('content')<section class="section"><h1>Services</h1>@foreach($categories as $category)<h2>{{ $category->localized_name }}</h2><div class="grid grid-3">@foreach($category->services as $service)<a class="card" href="{{ route('services.show',$service->slug) }}"><h3>{{ $service->localized_name }}</h3><p>{{ $service->localized_short_description }}</p><p class="muted">{{ $service->display_price }} • {{ $service->duration_minutes }} min</p></a>@endforeach</div>@endforeach</section>@endsection
