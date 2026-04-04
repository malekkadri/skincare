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
        :root { --bg:#F8F6F3; --secondary:#EDE7E1; --accent:#C8A27A; --text:#2E2E2E; --muted:#7A7A7A; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--text); font-family: 'Inter', system-ui, sans-serif; }
        h1,h2,h3 { margin-top:0; font-family: 'Playfair Display', Georgia, serif; }
        .wrap { width: min(980px, calc(100% - 2rem)); margin: 1.5rem auto 2rem; }
        .topbar { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom: 1rem; }
        .pill-group { display:flex; gap:.4rem; flex-wrap:wrap; }
        .card { background:#fffdfb; border:1px solid #e9dfd6; border-radius:22px; padding:1.2rem; box-shadow: 0 18px 42px rgba(95, 81, 69, .07); }
        .hero { background: linear-gradient(135deg, #f6efe8 0%, #fbf9f6 100%); border:1px solid #e7dcd1; border-radius:26px; padding:1.6rem; margin-bottom: 1rem; }
        .btn { background: var(--accent); color:#fff; border:1px solid transparent; padding:.72rem 1.1rem; border-radius:999px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
        .btn-soft { background: var(--secondary); color: var(--text); border-color:#ddd2c8; }
        .btn:disabled { opacity:.55; cursor:not-allowed; }
        .grid { display:grid; gap:1rem; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        input,select,textarea { width:100%; border:1px solid #ded4ca; border-radius:14px; padding:.7rem .85rem; background:#fffdfb; font:inherit; color:var(--text); }
        label { font-size:.88rem; color:#6b5e53; }
        .steps { display:grid; grid-template-columns:repeat(5, minmax(0, 1fr)); gap:.45rem; margin-bottom:1rem; }
        .step { text-align:center; padding:.45rem .6rem; border-radius:999px; background:#f1e9e1; color:#7b6651; font-size:.8rem; }
        .active { background: var(--accent); color: #fff; }
        .service-card { padding: 1rem; border: 1px solid #eaded3; border-radius: 16px; background: #fffcf9; }
        .field-error { color: #a03333; font-size: .83rem; margin-top: .35rem; }
        .notice { padding: .85rem 1rem; border-radius: 14px; margin-bottom: 1rem; border: 1px solid #efd8c7; background: #fef4ec; color: #704f37; }
        .title { margin: 0 0 .35rem; }
        .subtitle { color: #766558; margin: 0 0 1.2rem; font-size: .95rem; }
        @media (max-width: 760px) {
            .grid-2 { grid-template-columns: 1fr; }
            .steps { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="topbar">
        <h2>Skin by Noor — {{ __('booking.title') }}</h2>
        <div class="pill-group">
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="fr"><button class="btn {{ app()->getLocale()==='fr' ? '' : 'btn-soft' }}" type="submit">FR</button></form>
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="en"><button class="btn {{ app()->getLocale()==='en' ? '' : 'btn-soft' }}" type="submit">EN</button></form>
            <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="TND"><button class="btn {{ session('currency','TND')==='TND' ? '' : 'btn-soft' }}" type="submit">TND</button></form>
            <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="EUR"><button class="btn {{ session('currency','TND')==='EUR' ? '' : 'btn-soft' }}" type="submit">EUR</button></form>
        </div>
    </div>

    <div class="hero">
        <div class="steps">
            <span class="step {{ request()->routeIs('booking.service*') ? 'active' : '' }}">1. {{ __('booking.step_service') }}</span>
            <span class="step {{ request()->routeIs('booking.date*') ? 'active' : '' }}">2. {{ __('booking.step_date') }}</span>
            <span class="step {{ request()->routeIs('booking.slot*') ? 'active' : '' }}">3. {{ __('booking.step_slot') }}</span>
            <span class="step {{ request()->routeIs('booking.customer*') ? 'active' : '' }}">4. {{ __('booking.step_customer') }}</span>
            <span class="step {{ request()->routeIs('booking.review*') ? 'active' : '' }}">5. {{ __('booking.step_review') }}</span>
        </div>
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
