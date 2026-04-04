@extends('public.layouts.app')

@section('content')
<section class="section">
    <h1>Gallery</h1>
    @if($items->isEmpty())
        <div class="empty-state">Gallery images will be published soon.</div>
    @else
        <div class="grid grid-3">
            @foreach($items as $item)
                <div class="card">
                    <img src="{{ $item->image_url ?: 'https://via.placeholder.com/600x400?text=Skin+by+Noor' }}" alt="{{ $item->localized_title }}">
                    <p>{{ $item->localized_caption }}</p>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
