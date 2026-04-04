@extends('public.layouts.app')

@section('content')
<section class="section">
    <h1>FAQ</h1>
    @if($items->isEmpty())
        <div class="empty-state">FAQs are being prepared and will be published shortly.</div>
    @else
        @foreach($items as $cat => $faqs)
            <h3>{{ $cat }}</h3>
            @foreach($faqs as $faq)
                <details class="card"><summary>{{ $faq->localized_question }}</summary><p>{{ $faq->localized_answer }}</p></details>
            @endforeach
        @endforeach
    @endif
</section>
@endsection
