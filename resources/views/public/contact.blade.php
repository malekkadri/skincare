@extends('public.layouts.app')

@section('title', $settings->localized('contact_page_title') ?? 'Contact')

@section('content')
<section class="page-section">
    <div class="page-hero">
        <p class="section-kicker">Contact</p>
        <h1 class="section-title">{{ $settings->localized('contact_page_title') ?? 'Get in touch' }}</h1>
        <p class="muted">{{ $settings->localized('contact_intro') }}</p>
    </div>
</section>

<section class="page-section" style="padding-top:0;">
    <div class="grid grid-2">
        <article class="card">
            <h3>Visit or call us</h3>
            <p class="muted">{{ $settings->localized('address') }}</p>
            <p class="muted">{{ $settings->phone }}</p>
            @if(filled($settings->whatsapp_number))
                <a class="btn" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}">WhatsApp</a>
            @endif
        </article>
        <article class="card" style="padding:0;overflow:hidden;">
            @if($settings->map_embed_url)
                <iframe src="{{ $settings->map_embed_url }}" width="100%" height="320" style="border:0;display:block;" loading="lazy"></iframe>
            @else
                <div class="empty-state" style="height:100%;display:grid;place-items:center;">Map details will be available soon.</div>
            @endif
        </article>
    </div>
</section>
@endsection
