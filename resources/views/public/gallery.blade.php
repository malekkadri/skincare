@extends('public.layouts.app')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Gallery</p>
        <h1 class="section-title">Glow moments</h1>
        <p class="muted">A visual journey of Skin by Noor treatments, ambiance, and client care rituals.</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    @if($items->isEmpty())
        <div class="empty-state">Gallery images will be published soon.</div>
    @else
        <div class="grid grid-3">
            @foreach($items as $item)
                <article class="card" style="overflow:hidden;padding:0;">
                    <img
                        src="{{ $item->image_url ?: 'https://via.placeholder.com/600x400?text=Skin+by+Noor' }}"
                        alt="{{ $item->localized_title }}"
                        style="width:100%;height:250px;object-fit:cover;border-radius:22px 22px 0 0;"
                    >
                    <div style="padding:1rem 1.1rem 1.2rem;">
                        <h3 style="margin-bottom:.4rem;">{{ $item->localized_title }}</h3>
                        <p class="muted" style="margin:0;">{{ $item->localized_caption }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
