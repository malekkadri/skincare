@extends('public.layouts.app')
@section('title', $settings->site_name)
@section('content')
<section class="hero">
    <h1>{{ $sections['hero']->localized_title ?? $settings->localized('hero_title') }}</h1>
    <p class="muted">{{ $sections['hero']->localized_content ?? $settings->localized('hero_subtitle') }}</p>
    <div class="menu"><a class="btn" href="{{ route('booking.service') }}">{{ $sections['hero']->localized_button_text ?? $settings->localized('hero_button_text') }}</a><a class="btn btn-alt" href="{{ route('contact') }}">{{ $sections['hero']->localized_secondary_button_text ?? 'Contact' }}</a></div>
</section>
<section class="section"><h2>{{ $sections['intro']->localized_title ?? 'Skin by Noor' }}</h2><p>{{ $sections['intro']->localized_content ?? '' }}</p></section>
<section class="section"><h2>Featured Services</h2><div class="grid grid-3">@foreach($featuredServices as $service)<a class="card" href="{{ route('services.show',$service->slug) }}"><h3>{{ $service->localized_name }}</h3><p class="muted">{{ $service->display_price }} · {{ $service->duration_minutes }} min</p></a>@endforeach</div></section>
<section class="section"><h2>Gallery</h2><div class="grid grid-3">@foreach($featuredGallery as $img)<div class="card"><img src="{{ $img->image_url }}" alt="{{ $img->localized_title }}"></div>@endforeach</div></section>
<section class="section"><h2>Testimonials</h2><div class="grid grid-3">@foreach($featuredTestimonials as $t)<div class="card"><strong>{{ $t->client_name }}</strong><p>{{ $t->localized_content }}</p></div>@endforeach</div></section>
@endsection
