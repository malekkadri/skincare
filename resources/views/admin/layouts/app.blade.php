<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Skin by Noor</title>
    <style>
        :root { --bg:#f3f4f6; --card:#fff; --text:#111827; --muted:#6b7280; --primary:#7c3aed; --border:#e5e7eb; }
        *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text)}
        .layout{display:flex;min-height:100vh}.sidebar{width:240px;background:#111827;color:#f9fafb;padding:1.25rem}
        .brand{font-size:1.1rem;font-weight:700;margin-bottom:1.5rem}.nav a{display:block;padding:.65rem .75rem;border-radius:.5rem;color:#e5e7eb;text-decoration:none;margin-bottom:.4rem}
        .nav a.active,.nav a:hover{background:#1f2937}.content{flex:1}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center}
        .main{padding:1.5rem}.card{background:var(--card);border:1px solid var(--border);border-radius:.75rem;padding:1.25rem;margin-bottom:1rem}
        .card h2{margin-top:0;font-size:1.05rem}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}
        label{display:block;font-weight:600;font-size:.9rem;margin-bottom:.35rem} input,textarea,select{width:100%;padding:.6rem .7rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.95rem}
        textarea{min-height:90px}.btn{background:var(--primary);color:#fff;border:none;border-radius:.5rem;padding:.65rem .95rem;cursor:pointer}
        .flash{padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1rem}.flash.success{background:#dcfce7;color:#166534}
        .error{font-size:.8rem;color:#dc2626;margin-top:.25rem}.muted{color:var(--muted);font-size:.9rem}
        @media (max-width: 900px){.sidebar{display:none}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">Skin by Noor</div>
        <nav class="nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Website Settings</a>
        </nav>
    </aside>

    <div class="content">
        <header class="topbar">
            <strong>@yield('header', 'Admin')</strong>
            <form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit" class="btn">Logout</button></form>
        </header>

        <main class="main">
            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
