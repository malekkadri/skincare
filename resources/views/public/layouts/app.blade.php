<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo->title ?? ($settings->site_name ?? 'Asthetika') }}</title>
    <meta name="description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <link rel="canonical" href="{{ $seo->canonical ?? url()->current() }}">
    @if(filled($settings->favicon_url))
        <link rel="icon" href="{{ $settings->favicon_url }}">
    @endif
    <meta property="og:title" content="{{ $seo->title ?? ($settings->site_name ?? 'Asthetika') }}">
    <meta property="og:description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <meta property="og:type" content="{{ $seo->ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $seo->canonical ?? url()->current() }}">
    @if(!empty($seo?->image))<meta property="og:image" content="{{ $seo->image }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo->title ?? ($settings->site_name ?? 'Asthetika') }}">
    <meta name="twitter:description" content="{{ $seo->description ?? ($settings->localized('site_tagline') ?? '') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f2ed;
            --surface: #fdfaf7;
            --surface-strong: #fff;
            --surface-soft: #f2eae1;
            --border: #e7ddd2;
            --border-strong: #dacdbf;
            --accent: #b99068;
            --accent-deep: #926947;
            --text-primary: #2f2722;
            --text-secondary: #76685b;
            --focus-ring: rgba(185, 144, 104, .35);
            --shadow-soft: 0 16px 44px rgba(47, 39, 34, .08);
            --shadow-lift: 0 22px 46px rgba(47, 39, 34, .12);
            --radius-card: 22px;
            --radius-control: 14px;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background:
                radial-gradient(circle at 6% 12%, rgba(226, 206, 188, .28), transparent 40%),
                radial-gradient(circle at 92% 24%, rgba(240, 227, 213, .33), transparent 34%),
                var(--bg);
            color: var(--text-primary);
            font-family: 'Inter', system-ui, sans-serif;
            line-height: 1.7;
            min-height: 100vh;
            overflow-x: clip;
        }
        body.menu-open { overflow: hidden; }
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', Georgia, serif;
            letter-spacing: .02em;
            color: var(--text-primary);
            margin-top: 0;
        }
        a { color: inherit; text-decoration: none; }
        .container {
            width: min(1240px, calc(100% - 2.5rem));
            margin-inline: auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(18px);
            background: rgba(248, 243, 237, .88);
            border-bottom: 1px solid rgba(218, 205, 191, .72);
        }
        .site-nav {
            min-height: 92px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1.5rem;
            padding: .95rem 0;
        }
        .brand {
            display: inline-grid;
            gap: .2rem;
            align-items: center;
            justify-items: start;
        }
        .brand-logo {
            display: block;
            max-height: 58px;
            width: auto;
            object-fit: contain;
        }
        .brand-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.25rem, 2vw, 1.6rem);
            letter-spacing: .04em;
            line-height: 1.1;
        }
        .brand-subtitle {
            text-transform: uppercase;
            letter-spacing: .23em;
            font-size: .64rem;
            color: var(--text-secondary);
            margin-left: .03em;
        }

        .menu-toggle {
            display: none;
            border: 1px solid var(--border-strong);
            background: rgba(255,255,255,.65);
            color: var(--text-primary);
            border-radius: 999px;
            padding: .58rem .95rem;
            font-size: .9rem;
            font-weight: 600;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
        }
        .menu-toggle-lines {
            display: inline-grid;
            gap: 3px;
        }
        .menu-toggle-lines span {
            width: 16px;
            height: 1.5px;
            border-radius: 99px;
            background: currentColor;
            display: block;
        }

        .nav-links {
            justify-self: center;
            display: flex;
            align-items: center;
            gap: .35rem;
            flex-wrap: wrap;
        }
        .nav-link {
            color: var(--text-secondary);
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            font-size: .93rem;
            letter-spacing: .01em;
            padding: .55rem .8rem;
            border-radius: 999px;
            border: 1px solid transparent;
            transition: color .2s ease, background-color .2s ease, border-color .2s ease;
        }
        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, .7);
            border-color: rgba(218, 205, 191, .8);
        }
        .nav-link[aria-current="page"] {
            color: var(--text-primary);
            background: rgba(255, 255, 255, .88);
            border-color: var(--border-strong);
            box-shadow: 0 6px 16px rgba(47, 39, 34, .06);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: .55rem;
        }
        .locale-switcher {
            display: inline-flex;
            background: rgba(255, 255, 255, .72);
            border: 1px solid var(--border-strong);
            border-radius: 999px;
            padding: .22rem;
            gap: .2rem;
        }
        .locale-switcher form { margin: 0; }
        .locale-pill {
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: var(--text-secondary);
            font-size: .76rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-weight: 600;
            min-width: 2.4rem;
            padding: .42rem .62rem;
            cursor: pointer;
        }
        .locale-pill.is-active {
            background: #fff;
            color: var(--text-primary);
            box-shadow: 0 7px 16px rgba(47, 39, 34, .08);
        }

        .btn {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid transparent;
            color: #fff;
            padding: .74rem 1.35rem;
            font-size: .88rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: transform .22s ease, box-shadow .22s ease, opacity .2s ease;
            box-shadow: 0 14px 30px rgba(185, 144, 104, .28);
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-deep) 100%);
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(185, 144, 104, .34);
        }
        .btn-soft {
            background: var(--surface-soft);
            color: var(--text-primary);
            border-color: var(--border-strong);
            box-shadow: none;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
        }
        .btn-row { display: flex; flex-wrap: wrap; gap: .75rem; }

        .btn-soft:hover {
            background: #ecdfd1;
            border-color: var(--border-strong);
            box-shadow: 0 10px 24px rgba(47, 39, 34, .1);
        }
        .btn-block { width: 100%; }

        .section-intro {
            max-width: 70ch;
            display: grid;
            gap: .65rem;
            margin-bottom: 1rem;
        }
        .section-intro .section-title { margin-bottom: 0; }
        .section-intro .muted { margin: 0; }

        .centered-card {
            max-width: var(--card-max, 920px);
            margin-inline: auto;
        }
        .section-tight-top { padding-top: 0; }

        .sbn-page-hero{position:relative;border-radius:28px;overflow:hidden;min-height:320px;display:grid;align-items:end;box-shadow:var(--shadow-soft);border:1px solid var(--border);} 
        .sbn-page-hero img{width:100%;height:clamp(320px,45vw,520px);object-fit:cover;display:block;} 
        .sbn-page-hero .hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(36,26,18,.08),rgba(36,26,18,var(--hero-overlay)));}
        .sbn-page-hero .hero-content{position:absolute;left:clamp(1rem,3vw,2.4rem);bottom:clamp(1rem,3vw,2rem);max-width:min(90%,700px);color:#fff;z-index:2}
        .sbn-page-hero h1{margin:0 0 .35rem;color:#fff;font-size:clamp(1.8rem,4.2vw,3.2rem)}
        .sbn-page-hero .subtitle{margin:.25rem 0;font-size:clamp(1rem,2vw,1.25rem)}
        .sbn-page-hero .description{margin:.2rem 0 1rem;opacity:.95}


        .form-control,
        select,
        input,
        textarea {
            min-height: 44px;
            border-radius: var(--radius-control);
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }
        textarea { min-height: 128px; resize: vertical; }
        input::placeholder,
        textarea::placeholder { color: #9a8a7b; }
        .field-help {
            color: var(--text-secondary);
            font-size: .8rem;
            line-height: 1.45;
        }
        .form-note {
            color: var(--text-secondary);
            font-size: .9rem;
            margin: 0;
        }
        .form-checkbox { width: auto; margin-top: .35rem; }

        .soft-panel {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: linear-gradient(140deg, #fffdfa 0%, #f8f2eb 100%);
            padding: 1rem;
        }
        .stack-sm { display: grid; gap: .6rem; }
        .stack-md { display: grid; gap: 1rem; }


        main.container {
            padding-top: 1.35rem;
            padding-bottom: 2.4rem;
        }
        .page-section { padding: clamp(2.2rem, 5vw, 4rem) 0; }
        .page-hero {
            border-radius: 32px;
            padding: clamp(2rem, 4.8vw, 4.25rem);
            background: linear-gradient(145deg, #f8f1e9 0%, #fcfaf8 100%);
            border: 1px solid var(--border);
            margin-top: 1.4rem;
            box-shadow: var(--shadow-soft);
        }
        .section-title {
            font-size: clamp(1.9rem, 2.8vw, 2.85rem);
            margin-bottom: .8rem;
            font-weight: 500;
            line-height: 1.2;
        }
        .section-kicker {
            text-transform: uppercase;
            letter-spacing: .2em;
            font-size: .72rem;
            color: var(--text-secondary);
            margin-bottom: .5rem;
            font-weight: 600;
        }
        .muted { color: var(--text-secondary); }
        .card {
            background: var(--surface-strong);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
            padding: 1.35rem;
            transition: transform .28s ease, box-shadow .28s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lift);
        }
        .grid { display: grid; gap: 1.2rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .form-control,
        select,
        input,
        textarea {
            width: 100%;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-control);
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
        :is(input, select, textarea):focus-visible {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(185, 144, 104, .14);
            background: #fff;
        }
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
            margin-top: 2rem;
            border-top: 1px solid var(--border-strong);
            padding: clamp(3.2rem, 7vw, 5rem) 0 1.1rem;
            background: linear-gradient(180deg, #f2ebe3 0%, #ece3d8 100%);
        }
        .footer-grid {
            display: grid;
            gap: 1.8rem;
            grid-template-columns: 1.6fr 1fr 1fr 1.15fr;
            align-items: start;
        }

        .footer-brand {
            display: inline-grid;
            gap: .3rem;
            margin-bottom: .5rem;
        }
        .footer-logo {
            display: block;
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        .footer-title {
            font-size: 1.06rem;
            margin-bottom: .55rem;
        }
        .footer-meta {
            font-size: .9rem;
            color: var(--text-secondary);
            margin: .3rem 0;
        }
        .footer-link {
            color: var(--text-secondary);
            min-height: 34px;
            align-items: center;
            display: inline-flex;
            margin-bottom: .5rem;
            transition: color .2s ease;
        }
        .footer-link:hover { color: var(--text-primary); }
        .footer-note {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(218, 205, 191, .85);
            font-size: .82rem;
            color: var(--text-secondary);
            display: flex;
            flex-wrap: wrap;
            gap: .75rem 1.4rem;
            justify-content: space-between;
        }
        .footer-social {
            display: flex;
            gap: .55rem;
            flex-wrap: wrap;
        }
        .footer-social a {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 999px;
            border: 1px solid var(--border-strong);
            background: rgba(255,255,255,.65);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
        }
        .footer-social svg {
            width: 1.18rem;
            height: 1.18rem;
            stroke: currentColor;
            fill: none;
            flex: 0 0 auto;
        }
        .lux-divider {
            height: 1px;
            width: min(260px, 50vw);
            background: linear-gradient(90deg, transparent 0%, rgba(185, 144, 104, .8) 50%, transparent 100%);
            margin: 0 0 1.25rem;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px) scale(.99);
            transition: opacity .75s ease, transform .75s ease;
            transition-delay: var(--reveal-delay, 0s);
        }
        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        :is(a, button, input, textarea, select).is-gold-focus:focus-visible,
        :is(a, button, input, textarea, select):focus-visible {
            outline: 2px solid var(--focus-ring);
            outline-offset: 3px;
        }

        .mobile-panel {
            display: none;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
            .reveal { opacity: 1; transform: none; }
        }

        @media (max-width: 1080px) {
            .site-nav {
                grid-template-columns: auto auto;
                grid-template-areas:
                    "brand toggle"
                    "desktop desktop";
                align-items: center;
            }
            .brand { grid-area: brand; }
            .menu-toggle {
                grid-area: toggle;
                justify-self: end;
                display: inline-flex;
            }
            .nav-links,
            .nav-actions {
                display: none;
            }
            .mobile-panel {
                display: grid;
                gap: 1rem;
                padding: 1rem;
                margin-bottom: .65rem;
                border: 1px solid var(--border-strong);
                background: rgba(253, 250, 247, .98);
                border-radius: 20px;
                box-shadow: var(--shadow-soft);
            }
            .mobile-panel[hidden] { display: none; }
            .mobile-nav-links {
                display: grid;
                gap: .4rem;
            }
            .mobile-nav-links .nav-link {
                border-radius: var(--radius-control);
                padding: .68rem .85rem;
                border: 1px solid var(--border);
                background: rgba(255,255,255,.78);
            }
            .mobile-action-row {
                display: grid;
                grid-template-columns: 1fr;
                gap: .65rem;
            }
            .mobile-locale {
                justify-self: start;
            }
        }

        @media (max-width: 900px) {
            .footer-grid,
            .grid-3,
            .grid-2 { grid-template-columns: 1fr; }
            .page-section { padding: 72px 0; }
            .container { width: min(1240px, calc(100% - 1.45rem)); }
            .footer-note { flex-direction: column; gap: .5rem; }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container">
        <div class="site-nav">
            <a class="brand is-gold-focus" href="{{ route('home') }}">
                @if(filled($settings->logo_url))
                    <img class="brand-logo" src="{{ $settings->logo_url }}" alt="{{ $settings->site_name ?? 'Asthetika' }}">
                @else
                    <span class="brand-title">{{ $settings->site_name ?? 'Asthetika' }}</span>
                    <span class="brand-subtitle">Dr Aziz Sahly</span>
                @endif
            </a>

            <button class="menu-toggle is-gold-focus" type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobileNavPanel">
                <span class="menu-toggle-lines" aria-hidden="true"><span></span><span></span><span></span></span>
                <span>{{ __('public.nav.menu') }}</span>
            </button>

            <nav class="nav-links" aria-label="Primary navigation">
                <a class="nav-link" href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>{{ __('public.nav.about') }}</a>
                <a class="nav-link" href="{{ route('services.index') }}" @if(request()->routeIs('services.index', 'services.show')) aria-current="page" @endif>{{ __('public.nav.services') }}</a>
                <a class="nav-link" href="{{ route('recommender.index') }}" @if(request()->routeIs('recommender.index')) aria-current="page" @endif>{{ __('public.nav.ai_recommender') }}</a>
                <a class="nav-link" href="{{ route('consultation.create') }}" @if(request()->routeIs('consultation.create', 'consultation.success')) aria-current="page" @endif>{{ __('public.nav.consultation') }}</a>
                <a class="nav-link" href="{{ route('gallery') }}" @if(request()->routeIs('gallery')) aria-current="page" @endif>{{ __('public.nav.gallery') }}</a>
                <a class="nav-link" href="{{ route('testimonials') }}" @if(request()->routeIs('testimonials')) aria-current="page" @endif>{{ __('public.nav.testimonials') }}</a>
                <a class="nav-link" href="{{ route('faq') }}" @if(request()->routeIs('faq')) aria-current="page" @endif>{{ __('public.nav.faq') }}</a>
                <a class="nav-link" href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>{{ __('public.nav.contact') }}</a>
            </nav>

            <div class="nav-actions" aria-label="Header actions">
                <div class="locale-switcher" role="group" aria-label="{{ __('public.nav.language') }}">
                    <form method="POST" action="{{ route('preferences.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="fr">
                        <button class="locale-pill is-gold-focus {{ app()->getLocale()==='fr' ? 'is-active' : '' }}" type="submit">FR</button>
                    </form>
                    <form method="POST" action="{{ route('preferences.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="en">
                        <button class="locale-pill is-gold-focus {{ app()->getLocale()==='en' ? 'is-active' : '' }}" type="submit">EN</button>
                    </form>
                </div>
                <a class="btn is-gold-focus" href="{{ route('booking.service') }}">{{ __('public.nav.book_now') }}</a>
            </div>
        </div>

        <div class="mobile-panel" id="mobileNavPanel" hidden>
            <nav class="mobile-nav-links" aria-label="Mobile primary navigation">
                <a class="nav-link" href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>{{ __('public.nav.about') }}</a>
                <a class="nav-link" href="{{ route('services.index') }}" @if(request()->routeIs('services.index', 'services.show')) aria-current="page" @endif>{{ __('public.nav.services') }}</a>
                <a class="nav-link" href="{{ route('recommender.index') }}" @if(request()->routeIs('recommender.index')) aria-current="page" @endif>{{ __('public.nav.ai_recommender') }}</a>
                <a class="nav-link" href="{{ route('consultation.create') }}" @if(request()->routeIs('consultation.create', 'consultation.success')) aria-current="page" @endif>{{ __('public.nav.consultation') }}</a>
                <a class="nav-link" href="{{ route('gallery') }}" @if(request()->routeIs('gallery')) aria-current="page" @endif>{{ __('public.nav.gallery') }}</a>
                <a class="nav-link" href="{{ route('testimonials') }}" @if(request()->routeIs('testimonials')) aria-current="page" @endif>{{ __('public.nav.testimonials') }}</a>
                <a class="nav-link" href="{{ route('faq') }}" @if(request()->routeIs('faq')) aria-current="page" @endif>{{ __('public.nav.faq') }}</a>
                <a class="nav-link" href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>{{ __('public.nav.contact') }}</a>
            </nav>
            <div class="mobile-action-row">
                <div class="locale-switcher mobile-locale" role="group" aria-label="{{ __('public.nav.language') }}">
                    <form method="POST" action="{{ route('preferences.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="fr">
                        <button class="locale-pill is-gold-focus {{ app()->getLocale()==='fr' ? 'is-active' : '' }}" type="submit">FR</button>
                    </form>
                    <form method="POST" action="{{ route('preferences.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="en">
                        <button class="locale-pill is-gold-focus {{ app()->getLocale()==='en' ? 'is-active' : '' }}" type="submit">EN</button>
                    </form>
                </div>
                <a class="btn is-gold-focus" href="{{ route('booking.service') }}">{{ __('public.nav.book_now') }}</a>
            </div>
        </div>
    </div>
</header>

<main class="container">
    @yield('content')
</main>

@php
    $footerMapUrl = trim((string) ($settings->map_embed_url ?? ''));
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="footer-brand is-gold-focus" href="{{ route('home') }}">
                    @if(filled($settings->logo_url))
                        <img class="footer-logo" src="{{ $settings->logo_url }}" alt="{{ $settings->site_name ?? 'Asthetika' }}">
                    @else
                        <span class="brand-title">{{ $settings->site_name ?? 'Asthetika' }}</span>
                        <span class="brand-subtitle">Dr Aziz Sahly</span>
                    @endif
                </a>
                <div class="lux-divider"></div>
                <p class="muted">{{ $settings->localized('site_tagline') ?: __('public.footer.tagline') }}</p>
                <p class="footer-meta">{{ __('public.footer.brand_assurance') }}</p>
            </div>
            <div>
                <h4 class="footer-title">{{ __('public.footer.quick_links') }}</h4>
                <a class="footer-link" href="{{ route('home') }}">{{ __('public.footer.home') }}</a>
                <a class="footer-link" href="{{ route('about') }}">{{ __('public.footer.who_we_are') }}</a>
                <a class="footer-link" href="{{ route('gallery') }}">{{ __('public.nav.gallery') }}</a>
                <a class="footer-link" href="{{ route('testimonials') }}">{{ __('public.nav.testimonials') }}</a>
                <a class="footer-link" href="{{ route('faq') }}">{{ __('public.nav.faq') }}</a>
            </div>
            <div>
                <h4 class="footer-title">{{ __('public.footer.services') }}</h4>
                <a class="footer-link" href="{{ route('services.index') }}">{{ __('public.nav.services') }}</a>
                <a class="footer-link" href="{{ route('consultation.create') }}">{{ __('public.nav.consultation') }}</a>
                <a class="footer-link" href="{{ route('recommender.index') }}">{{ __('public.nav.ai_recommender') }}</a>
                <a class="footer-link" href="{{ route('booking.service') }}">{{ __('public.footer.book_appointment') }}</a>
            </div>
            <div>
                <h4 class="footer-title">{{ __('public.footer.contact') }}</h4>
                <a class="footer-link" href="{{ route('contact') }}">{{ __('public.footer.contact_page') }}</a>
                @if(filled($settings->phone))
                    <a class="footer-link" href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}">{{ $settings->phone }}</a>
                @endif
                @if(filled($settings->localized('address')))
                    <span class="footer-meta">{{ $settings->localized('address') }}</span>
                @endif
                @if(filled($settings->whatsapp_number))
                    <a class="btn is-gold-focus" target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}">{{ __('public.footer.whatsapp') }}</a>
                @endif
                <div class="footer-social" aria-label="{{ __('public.footer.social') }}">
                    @if(filled($settings->instagram_url))
                        <a class="is-gold-focus" href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke-width="1.8"></rect><circle cx="12" cy="12" r="4.2" stroke-width="1.8"></circle><circle cx="17.3" cy="6.7" r="1"></circle></svg>
                        </a>
                    @endif
                    @if(filled($settings->facebook_url))
                        <a class="is-gold-focus" href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V5h-3c-2.8 0-5 2.2-5 5v3H6v4h3v4h4v-4h3.2l.8-4H13v-2.8c0-.7.3-1.2 1-1.2Z" stroke-width="1.6"></path></svg>
                        </a>
                    @endif
                    @if(filled($settings->phone))
                        <a class="is-gold-focus" href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}" aria-label="Call Asthetika">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 16.2v2.7a2 2 0 0 1-2.2 2 19.7 19.7 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.7 19.7 0 0 1 1 3.2 2 2 0 0 1 3 1h2.7a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L6.7 8.7a16 16 0 0 0 6.6 6.6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7A2 2 0 0 1 21 16.2z" stroke-width="1.8"></path></svg>
                        </a>
                    @endif
                    @if(filled($settings->whatsapp_number))
                        <a class="is-gold-focus" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_number) }}" target="_blank" rel="noopener noreferrer" aria-label="Message Asthetika on WhatsApp">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11.8c0 4.8-3.9 8.7-8.7 8.7-1.5 0-2.9-.4-4.1-1l-4.2 1.3 1.4-4c-.8-1.4-1.2-3-1.2-4.7C3.2 7.3 7.1 3.4 12 3.4s8 3.9 8 8.4Z" stroke-width="1.6"></path><path d="M8.5 9.2c.3-.6.6-.6.9-.6h.7c.2 0 .4 0 .5.3l.8 1.8c.1.2 0 .4-.1.6l-.4.5c-.1.1-.2.3-.1.5.2.5.8 1.3 1.7 1.9.5.4 1 .6 1.4.8.2.1.4.1.6 0l.6-.5c.2-.1.4-.2.6-.1l1.7.8c.3.1.3.3.3.5v.7c0 .3-.1.6-.6.9-.5.3-1.1.5-1.9.3-1-.2-2.2-.7-3.6-1.9-1.2-1-2.1-2.3-2.5-3.5-.3-.9-.1-1.8.4-2.5Z" stroke-width="1.2"></path></svg>
                        </a>
                    @endif
                    @if(filled($footerMapUrl))
                        <a class="is-gold-focus" href="{{ $footerMapUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Open Asthetika location on Google Maps">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z" stroke-width="1.8"></path><circle cx="12" cy="10" r="2.7" stroke-width="1.8"></circle></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-note">
            <span>© {{ now()->year }} {{ $settings->site_name ?? 'Asthetika' }}. {{ __('public.footer.rights') }}</span>
            <span>
                <a class="footer-link" href="{{ route('policies.show', ['policy' => app()->getLocale()==='fr' ? 'confidentialite' : 'privacy']) }}">{{ __('public.footer.privacy') }}</a>
                ·
                <a class="footer-link" href="{{ route('policies.show', ['policy' => app()->getLocale()==='fr' ? 'conditions-generales' : 'terms']) }}">{{ __('public.footer.terms') }}</a>
            </span>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const revealElements = document.querySelectorAll('.reveal');
        if (!revealElements.length || !('IntersectionObserver' in window)) {
            revealElements.forEach((element) => element.classList.add('is-visible'));
        } else {
            revealElements.forEach((element, index) => {
                element.style.setProperty('--reveal-delay', `${Math.min(index * 0.04, 0.18)}s`);
            });

            const observer = new IntersectionObserver((entries, instance) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    entry.target.classList.add('is-visible');
                    instance.unobserve(entry.target);
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -6% 0px' });

            revealElements.forEach((element) => observer.observe(element));
        }

        const mobileToggle = document.querySelector('[data-mobile-toggle]');
        const mobilePanel = document.getElementById('mobileNavPanel');
        if (!mobileToggle || !mobilePanel) {
            return;
        }

        const closeMobileMenu = () => {
            mobilePanel.hidden = true;
            mobileToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
        };

        const openMobileMenu = () => {
            mobilePanel.hidden = false;
            mobileToggle.setAttribute('aria-expanded', 'true');
            document.body.classList.add('menu-open');
        };

        mobileToggle.addEventListener('click', () => {
            if (mobilePanel.hidden) {
                openMobileMenu();
                return;
            }
            closeMobileMenu();
        });

        mobilePanel.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMobileMenu);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !mobilePanel.hidden) {
                closeMobileMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1080 && !mobilePanel.hidden) {
                closeMobileMenu();
            }
        });
    });
</script>
</body>
</html>
