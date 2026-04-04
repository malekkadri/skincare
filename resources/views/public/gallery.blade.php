@extends('public.layouts.app')
@section('title', 'Gallery')
@section('content')<section class="section"><h1>Gallery</h1><div class="grid grid-3">@foreach($items as $item)<div class="card"><img src="{{ $item->image_url }}" alt="{{ $item->localized_title }}"><p>{{ $item->localized_caption }}</p></div>@endforeach</div></section>@endsection
