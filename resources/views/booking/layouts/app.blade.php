<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('booking.title') }} - Skin by Noor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f2ed;
            --bg-soft: #fbf7f2;
            --surface: #fffdfb;
            --surface-muted: #f5ede4;
            --accent: #b99068;
            --accent-soft: #efe2d4;
            --text: #2f2722;
            --muted: #76685b;
            --line: #e7ddd2;
            --line-strong: #dacdbf;
            --ok: #2e6a4f;
            --danger: #a03333;
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow: 0 16px 44px rgba(47, 39, 34, .08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text);
            background:
                radial-gradient(1300px 500px at 30% -15%, #fcf8f3 0%, transparent 70%),
                linear-gradient(180deg, #f8f5f1 0%, var(--bg) 45%, #f5f1ec 100%);
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            margin-top: 0;
            font-family: 'Playfair Display', Georgia, serif;
            letter-spacing: 0.01em;
        }

        .wrap {
            width: min(1020px, calc(100% - 1.75rem));
            margin: clamp(1rem, 2.2vw, 1.8rem) auto clamp(1.75rem, 3vw, 2.5rem);
        }

        .hero {
            background: linear-gradient(150deg, #f8efe5 0%, var(--surface) 65%);
            border: 1px solid var(--line);
            border-radius: calc(var(--radius-lg) + 2px);
            box-shadow: var(--shadow);
            padding: clamp(1rem, 2.2vw, 1.7rem);
            margin-bottom: 1rem;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .brand-block h2 {
            margin: 0;
            font-size: clamp(1.3rem, 1rem + 1vw, 1.8rem);
        }

        .brand-block p {
            margin: .35rem 0 0;
            color: var(--muted);
            font-size: .92rem;
        }

        .pill-group {
            display: flex;
            gap: .42rem;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
        }

        .pill-group form { margin: 0; }

        .steps {
            display: flex;
            gap: .55rem;
            overflow-x: auto;
            padding: .15rem 0 .25rem;
            scrollbar-width: thin;
        }

        .step {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            white-space: nowrap;
            padding: .55rem .9rem;
            border-radius: 999px;
            border: 1px solid #e4d7ca;
            background: rgba(255, 251, 246, .85);
            color: #7a6a5a;
            font-size: .79rem;
            font-weight: 500;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .step-index {
            width: 1.25rem;
            height: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid currentColor;
            font-size: .69rem;
            font-weight: 600;
        }

        .step.active {
            background: linear-gradient(180deg, #c89f75 0%, var(--accent) 100%);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 10px 22px rgba(171, 126, 84, .22);
        }

        .step.done {
            background: #f4ece3;
            border-color: #d9c3ad;
            color: #6e5a47;
        }

        .trust-note {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-top: .85rem;
            color: #6e6054;
            font-size: .84rem;
        }

        .trust-note svg {
            flex-shrink: 0;
            color: var(--ok);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: clamp(1rem, 2.1vw, 1.4rem);
            box-shadow: var(--shadow);
        }

        .grid { display: grid; gap: 1rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .service-card {
            padding: .95rem;
            border: 1px solid #e9ddd1;
            border-radius: var(--radius-md);
            background: #fffdfa;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .service-card:has(input:checked) {
            border-color: var(--line-strong);
            box-shadow: 0 0 0 2px rgba(183, 136, 91, .12);
        }

        input, select, textarea {
            width: 100%;
            min-height: 44px;
            border: 1px solid #dfd4c9;
            border-radius: 14px;
            padding: .72rem .88rem;
            background: #fffdfb;
            font: inherit;
            color: var(--text);
        }
        textarea { min-height: 118px; resize: vertical; }

        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible,
        .btn:focus-visible {
            outline: 2px solid rgba(183, 136, 91, .5);
            outline-offset: 2px;
        }

        label {
            display: inline-block;
            margin-bottom: .38rem;
            font-size: .86rem;
            font-weight: 500;
            color: #685c51;
        }

        .btn {
            background: linear-gradient(180deg, #c99f76 0%, var(--accent) 100%);
            color: #fff;
            border: 1px solid transparent;
            padding: .72rem 1.2rem;
            min-height: 44px;
            letter-spacing: .03em;
            border-radius: 999px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
        }

        .btn:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(150, 108, 68, .24); }
        .btn:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }

        .btn-soft {
            background: #efe3d6;
            color: var(--text);
            border-color: #ddcec1;
            box-shadow: none;
        }

        .field-error {
            color: var(--danger);
            font-size: .83rem;
            margin-top: .35rem;
        }

        .notice {
            padding: .85rem 1rem;
            border-radius: 14px;
            margin-bottom: 1rem;
            border: 1px solid #efd8c7;
            background: #fef4ec;
            color: #704f37;
        }

        .title { margin: 0 0 .35rem; }
        .subtitle { color: #76695d; margin: 0 0 1.15rem; font-size: .95rem; }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }

        @media (max-width: 760px) {
            .wrap { width: min(100%, calc(100% - 1rem)); }
            .topbar { flex-direction: column; align-items: stretch; }
            .pill-group { justify-content: flex-start; }
            .grid-2 { grid-template-columns: 1fr; }
            .btn { min-height: 44px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="hero">
        <div class="topbar">
            <div class="brand-block">
                <h2>Skin by Noor — {{ __('booking.title') }}</h2>
                <p>{{ __('booking.step_service') }} → {{ __('booking.step_review') }} · {{ __('booking.success') }}</p>
            </div>

            <div class="pill-group" aria-label="Language and currency preferences">
                <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="fr"><button class="btn {{ app()->getLocale()==='fr' ? '' : 'btn-soft' }}" type="submit">FR</button></form>
                <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="en"><button class="btn {{ app()->getLocale()==='en' ? '' : 'btn-soft' }}" type="submit">EN</button></form>
                <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="TND"><button class="btn {{ session('currency','TND')==='TND' ? '' : 'btn-soft' }}" type="submit">TND</button></form>
                <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="EUR"><button class="btn {{ session('currency','TND')==='EUR' ? '' : 'btn-soft' }}" type="submit">EUR</button></form>
            </div>
        </div>

        <div class="steps" aria-label="Booking progress">
            <span class="step {{ request()->routeIs('booking.service*') ? 'active' : (request()->routeIs('booking.date*') || request()->routeIs('booking.slot*') || request()->routeIs('booking.customer*') || request()->routeIs('booking.review*') || request()->routeIs('booking.success*') ? 'done' : '') }}"><span class="step-index">1</span>{{ __('booking.step_service') }}</span>
            <span class="step {{ request()->routeIs('booking.date*') ? 'active' : (request()->routeIs('booking.slot*') || request()->routeIs('booking.customer*') || request()->routeIs('booking.review*') || request()->routeIs('booking.success*') ? 'done' : '') }}"><span class="step-index">2</span>{{ __('booking.step_date') }}</span>
            <span class="step {{ request()->routeIs('booking.slot*') ? 'active' : (request()->routeIs('booking.customer*') || request()->routeIs('booking.review*') || request()->routeIs('booking.success*') ? 'done' : '') }}"><span class="step-index">3</span>{{ __('booking.step_slot') }}</span>
            <span class="step {{ request()->routeIs('booking.customer*') ? 'active' : (request()->routeIs('booking.review*') || request()->routeIs('booking.success*') ? 'done' : '') }}"><span class="step-index">4</span>{{ __('booking.step_customer') }}</span>
            <span class="step {{ request()->routeIs('booking.review*') ? 'active' : (request()->routeIs('booking.success*') ? 'done' : '') }}"><span class="step-index">5</span>{{ __('booking.step_review') }}</span>
        </div>

        <p class="trust-note">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-3 8.7-7 10-4-1.3-7-5.5-7-10V6l7-3z" stroke="currentColor" stroke-width="1.5"/><path d="M9 12.2l2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('booking.confirm') }} · {{ __('booking.success') }}
        </p>
    </div>

    @if(session('error'))
        <div class="notice">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="notice">
            <strong>Please check the form and try again.</strong>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
