<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo->title ?? ($settings->site_name ?? 'Skin by Noor') }}</title>
    <meta name="description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <link rel="canonical" href="{{ $seo->canonical ?? url()->current() }}">
    <meta property="og:title" content="{{ $seo->title ?? ($settings->site_name ?? 'Skin by Noor') }}">
    <meta property="og:description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <meta property="og:type" content="{{ $seo->ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $seo->canonical ?? url()->current() }}">
    @if(!empty($seo?->image))<meta property="og:image" content="{{ $seo->image }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->title ?? ($settings->site_name ?? 'Skin by Noor') }}">
    <meta name="twitter:description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <style>
        body{margin:0;font-family:Inter,system-ui,sans-serif;background:#f8f5f3;color:#2f2630;line-height:1.6}.container{max-width:1100px;margin:auto;padding:1rem}
        header{position:sticky;top:0;background:#fff8f6f2;backdrop-filter:blur(6px);border-bottom:1px solid #f0e6e2;z-index:10}
        .nav{display:flex;justify-content:space-between;align-items:center;gap:1rem}.menu{display:flex;gap:1rem;flex-wrap:wrap;align-items:center}
        a{text-decoration:none;color:inherit}.btn{background:#7c4d63;color:#fff;padding:.6rem 1rem;border-radius:999px;display:inline-block;border:0}
        .btn-alt{background:#efe4df;color:#47313d}.card{background:#fff;border:1px solid #eee1db;border-radius:16px;padding:1rem;box-shadow:0 6px 20px rgba(86,52,68,.06)}
        .grid{display:grid;gap:1rem}.hero{padding:4rem 0}.grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}.grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}
        footer{margin-top:3rem;background:#2f2630;color:#f8edf2;padding:2.5rem 0}.muted{opacity:.8}.section{padding:3rem 0}img{max-width:100%;border-radius:14px}
        .empty-state{padding:1rem;border:1px dashed #d8c2cb;border-radius:12px;background:#fff}
        @media(max-width:900px){.grid-3,.grid-2{grid-template-columns:1fr}.nav{flex-direction:column;align-items:flex-start}.menu{width:100%}}
    </style>
</head>
<body>
<header>
    <div class="container nav">
        <a href="{{ route('home') }}"><strong>{{ $settings->site_name ?? 'Skin by Noor' }}</strong></a>
        <nav class="menu">
            <a href="{{ route('about') }}">About</a><a href="{{ route('services.index') }}">Services</a><a href="{{ route('consultation.create') }}">Consultation</a>
            <a href="{{ route('recommender.index') }}">AI Recommender</a><a href="{{ route('gallery') }}">Gallery</a><a href="{{ route('testimonials') }}">Testimonials</a>
            <a href="{{ route('faq') }}">FAQ</a><a href="{{ route('contact') }}">Contact</a>
        </nav>
        <div class="menu">
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="fr"><button class="btn btn-alt" type="submit">FR</button></form>
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="en"><button class="btn btn-alt" type="submit">EN</button></form>
            <a class="btn" href="{{ route('booking.service') }}">Book</a>
        </div>
    </div>
</header>
<div class="container">@yield('content')</div>
<footer>
    <div class="container grid grid-3">
        <div><h4>{{ $settings->site_name ?? 'Skin by Noor' }}</h4><p class="muted">{{ $settings->localized('site_tagline') }}</p></div>
        <div><h4>Policies</h4>@foreach(\App\Models\Policy::active()->ordered()->get() as $footerPolicy)<div><a href="{{ route('policies.show',$footerPolicy) }}">{{ $footerPolicy->localized_title }}</a></div>@endforeach</div>
        <div><h4>WhatsApp</h4><a class="btn" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number ?? '') }}">{{ $settings->whatsapp_number ?: '+216' }}</a></div>
    </div>
</footer>
</body>
</html>
