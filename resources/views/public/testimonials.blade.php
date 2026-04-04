@extends('public.layouts.app')

@section('content')
<section class="section">
    <h1>Testimonials</h1>
    @if($items->isEmpty())
        <div class="empty-state">Client testimonials will appear here once published.</div>
    @else
        <div class="grid grid-3">
            @foreach($items as $t)
                <div class="card">
                    <p>{{ str_repeat('★',$t->rating) }}</p>
                    <p>{{ $t->localized_content }}</p>
                    <strong>{{ $t->client_name }}</strong>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
