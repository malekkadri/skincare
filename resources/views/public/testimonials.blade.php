@extends('public.layouts.app')
@section('title', 'Testimonials')
@section('content')<section class="section"><h1>Testimonials</h1><div class="grid grid-3">@foreach($items as $t)<div class="card"><p>{{ str_repeat('★',$t->rating) }}</p><p>{{ $t->localized_content }}</p><strong>{{ $t->client_name }}</strong></div>@endforeach</div></section>@endsection
