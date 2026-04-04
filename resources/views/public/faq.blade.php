@extends('public.layouts.app')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">FAQ</p>
        <h1 class="section-title">Frequently asked questions</h1>
        <p class="muted">Everything you need to know before your appointment.</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    @if($items->isEmpty())
        <div class="empty-state">FAQs are being prepared and will be published shortly.</div>
    @else
        <div class="grid" style="gap:1.8rem;">
            @foreach($items as $cat => $faqs)
                <section>
                    <h3 style="margin-bottom:.9rem;">{{ $cat }}</h3>
                    <div class="grid" style="gap:.9rem;">
                        @foreach($faqs as $faq)
                            <details class="card" style="padding:1rem 1.15rem;">
                                <summary style="cursor:pointer;font-weight:600;">{{ $faq->localized_question }}</summary>
                                <p class="muted" style="margin:.8rem 0 0;">{{ $faq->localized_answer }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</section>
@endsection
