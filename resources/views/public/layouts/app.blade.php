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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F8F6F3;
            --secondary: #EDE7E1;
            --accent: #C8A27A;
            --text-primary: #2E2E2E;
            --text-secondary: #7A7A7A;
            --card: #FFFCF9;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text-primary);
            font-family: 'Inter', system-ui, sans-serif;
            line-height: 1.7;
        }
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', Georgia, serif;
            letter-spacing: .02em;
            color: var(--text-primary);
            margin-top: 0;
        }
        a { color: inherit; text-decoration: none; }
        .container {
            width: min(1200px, calc(100% - 2.5rem));
            margin-inline: auto;
        }
        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(8px);
            background: rgba(248, 246, 243, .92);
            border-bottom: 1px solid #e8e1d9;
        }
        .site-nav {
            min-height: 84px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: .75rem 0;
        }
        .brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.45rem;
        }
        .menu {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            color: var(--text-secondary);
            font-size: .95rem;
        }
        .menu a:hover { color: var(--text-primary); }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid transparent;
            background: var(--accent);
            color: #fff;
            padding: .72rem 1.4rem;
            font-size: .94rem;
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
            box-shadow: 0 12px 30px rgba(200, 162, 122, .25);
            cursor: pointer;
        }
        .btn:hover { transform: translateY(-1px); opacity: .96; }
        .btn-soft {
            background: var(--secondary);
            border-color: #ddd2c8;
            color: var(--text-primary);
            box-shadow: none;
        }
        .btn-row { display: flex; flex-wrap: wrap; gap: .75rem; }
        .page-section { padding: 88px 0; }
        .page-hero {
            border-radius: 30px;
            padding: clamp(2rem, 4.5vw, 4rem);
            background: linear-gradient(135deg, #f6efe8 0%, #fbf9f6 100%);
            border: 1px solid #e7dcd1;
            margin-top: 1.25rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 2.5vw, 2.6rem);
            margin-bottom: .75rem;
            font-weight: 500;
        }
        .section-kicker {
            text-transform: uppercase;
            letter-spacing: .2em;
            font-size: .74rem;
            color: var(--text-secondary);
            margin-bottom: .5rem;
        }
        .muted { color: var(--text-secondary); }
        .card {
            background: var(--card);
            border-radius: 22px;
            border: 1px solid #ede5dd;
            box-shadow: 0 18px 45px rgba(95, 81, 69, .08);
            padding: 1.35rem;
        }
        .grid { display: grid; gap: 1.2rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .form-control,
        select,
        input,
        textarea {
            width: 100%;
            border: 1px solid #ded4ca;
            border-radius: 14px;
            padding: .78rem .92rem;
            background: #fffdfb;
            color: var(--text-primary);
            font: inherit;
        }
        label {
            font-size: .88rem;
            color: #6b5e53;
            display: inline-block;
            margin-bottom: .35rem;
        }
        .form-field { display: grid; gap: .35rem; }
        .form-span-full { grid-column: 1 / -1; }
        .error { color: #9f5748; font-size: .84rem; }
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            border: 1px dashed #d9cec2;
            border-radius: 18px;
            color: var(--text-secondary);
            background: #fbf8f4;
        }
        .site-footer {
            margin-top: 40px;
            border-top: 1px solid #e9dfd6;
            padding: 64px 0;
            background: #f3eee8;
        }
        .footer-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1.4fr 1fr 1fr;
        }
        .footer-link { color: var(--text-secondary); display: block; margin-bottom: .45rem; }
        .footer-link:hover { color: var(--text-primary); }
        @media (max-width: 900px) {
            .site-nav { justify-content: center; }
            .menu { justify-content: center; }
            .footer-grid,
            .grid-3,
            .grid-2 { grid-template-columns: 1fr; }
            .page-section { padding: 72px 0; }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container site-nav">
        <a class="brand" href="{{ route('home') }}">{{ $settings->site_name ?? 'Skin by Noor' }}</a>

        <nav class="menu" aria-label="Primary navigation">
            <a href="{{ route('about') }}">{{ __('public.nav.about') }}</a>
            <a href="{{ route('services.index') }}">{{ __('public.nav.services') }}</a>
            <a href="{{ route('gallery') }}">{{ __('public.nav.gallery') }}</a>
            <a href="{{ route('testimonials') }}">{{ __('public.nav.testimonials') }}</a>
            <a href="{{ route('contact') }}">{{ __('public.nav.contact') }}</a>
        </nav>

        <div class="menu">
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="fr"><button class="btn {{ app()->getLocale()==='fr' ? '' : 'btn-soft' }}" type="submit">FR</button></form>
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="en"><button class="btn {{ app()->getLocale()==='en' ? '' : 'btn-soft' }}" type="submit">EN</button></form>
            <a class="btn" href="{{ route('booking.service') }}">{{ __('public.nav.book_now') }}</a>
        </div>
    </div>
</header>

<main class="container">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h3>{{ $settings->site_name ?? 'Skin by Noor' }}</h3>
            <p class="muted">{{ $settings->localized('site_tagline') ?: __('public.footer.tagline') }}</p>
        </div>
        <div>
            <h4>{{ __('public.footer.explore') }}</h4>
            <a class="footer-link" href="{{ route('about') }}">{{ __('public.footer.who_we_are') }}</a>
            <a class="footer-link" href="{{ route('services.index') }}">{{ __('public.nav.services') }}</a>
            <a class="footer-link" href="{{ route('booking.service') }}">{{ __('public.footer.book_appointment') }}</a>
        </div>
        <div>
            <h4>{{ __('public.footer.contact') }}</h4>
            <a class="footer-link" href="{{ route('contact') }}">{{ __('public.footer.contact_page') }}</a>
            @if(filled($settings->whatsapp_number))
                <a class="btn" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}">{{ __('public.footer.whatsapp') }}</a>
            @endif
        </div>
    </div>
</footer>
</body>
</html>
