<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('booking.title') }} - Skin by Noor</title>
    <style>
        body{margin:0;background:#faf7f6;color:#2d1f23;font-family:Inter,system-ui,sans-serif}.wrap{max-width:900px;margin:0 auto;padding:1rem}
        .card{background:#fff;border:1px solid #f0e8e6;border-radius:16px;padding:1.2rem;margin-top:1rem;box-shadow:0 8px 22px rgba(0,0,0,.03)}
        .btn{background:#7d4b62;color:#fff;border:0;padding:.68rem 1rem;border-radius:10px;cursor:pointer;text-decoration:none;display:inline-block}
        .grid{display:grid;gap:1rem}.grid-2{grid-template-columns:repeat(2,minmax(0,1fr))} @media(max-width:760px){.grid-2{grid-template-columns:1fr}}
        input,select,textarea{width:100%;padding:.65rem;border:1px solid #ddd;border-radius:10px}.steps{display:flex;gap:.45rem;flex-wrap:wrap}.step{padding:.3rem .65rem;border-radius:999px;background:#f1e9ed;font-size:.82rem}
        .active{background:#7d4b62;color:#fff}.service-card{padding:1rem;border:1px solid #ecdfe3;border-radius:12px}
    </style>
</head>
<body>
<div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <h2>Skin by Noor — {{ __('booking.title') }}</h2>
        <div style="display:flex;gap:.4rem;">
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="fr"><button class="btn" style="background:{{ app()->getLocale()==='fr'?'#2d1f23':'#7d4b62' }}">FR</button></form>
            <form method="POST" action="{{ route('preferences.locale') }}">@csrf<input type="hidden" name="locale" value="en"><button class="btn" style="background:{{ app()->getLocale()==='en'?'#2d1f23':'#7d4b62' }}">EN</button></form>
            <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="TND"><button class="btn" style="background:{{ session('currency','TND')==='TND'?'#2d1f23':'#7d4b62' }}">TND</button></form>
            <form method="POST" action="{{ route('preferences.currency') }}">@csrf<input type="hidden" name="currency" value="EUR"><button class="btn" style="background:{{ session('currency','TND')==='EUR'?'#2d1f23':'#7d4b62' }}">EUR</button></form>
        </div>
    </div>

    <div class="steps">
        <span class="step {{ request()->routeIs('booking.service*') ? 'active' : '' }}">1. {{ __('booking.step_service') }}</span>
        <span class="step {{ request()->routeIs('booking.date*') ? 'active' : '' }}">2. {{ __('booking.step_date') }}</span>
        <span class="step {{ request()->routeIs('booking.slot*') ? 'active' : '' }}">3. {{ __('booking.step_slot') }}</span>
        <span class="step {{ request()->routeIs('booking.customer*') ? 'active' : '' }}">4. {{ __('booking.step_customer') }}</span>
        <span class="step {{ request()->routeIs('booking.review*') ? 'active' : '' }}">5. {{ __('booking.step_review') }}</span>
    </div>

    @yield('content')
</div>
</body>
</html>
