@extends('public.layouts.app')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Testimonials</p>
        <h1 class="section-title">Loved by our clients</h1>
        <p class="muted">Words from clients who trust Skin by Noor for personalized skincare experiences.</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    @if($items->isEmpty())
        <div class="empty-state">Client testimonials will appear here once published.</div>
    @else
        <div class="grid grid-3">
            @foreach($items as $t)
                <article class="card" style="background:#f8f1ea;">
                    <p style="color:#c89f74;font-size:1.05rem;letter-spacing:.08em;">{{ str_repeat('★', $t->rating) }}</p>
                    <p>{{ $t->localized_content }}</p>
                    <strong>{{ $t->client_name }}</strong>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
