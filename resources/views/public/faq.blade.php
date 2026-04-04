@extends('public.layouts.app')
@section('title', 'FAQ')
@section('content')<section class="section"><h1>FAQ</h1>@foreach($items as $cat => $faqs)<h3>{{ $cat }}</h3>@foreach($faqs as $faq)<details class="card"><summary>{{ $faq->localized_question }}</summary><p>{{ $faq->localized_answer }}</p></details>@endforeach @endforeach</section>@endsection
