<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Skin by Noor</title>
    <style>
        :root { --bg:#f3f4f6; --card:#fff; --text:#111827; --muted:#6b7280; --primary:#7c3aed; --border:#e5e7eb; --danger:#dc2626; --ok:#166534; }
        *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text)}
        .layout{display:flex;min-height:100vh}.sidebar{width:250px;background:#111827;color:#f9fafb;padding:1.25rem}
        .brand{font-size:1.1rem;font-weight:700;margin-bottom:1.5rem}.nav a{display:block;padding:.65rem .75rem;border-radius:.5rem;color:#e5e7eb;text-decoration:none;margin-bottom:.4rem}
        .nav a.active,.nav a:hover{background:#1f2937}.content{flex:1}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center}
        .main{padding:1.5rem}.card{background:var(--card);border:1px solid var(--border);border-radius:.75rem;padding:1.25rem;margin-bottom:1rem}
        .card h2{margin-top:0;font-size:1.05rem}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}.kpi-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:1rem}.kpi .value{font-size:1.5rem;font-weight:700}.kpi .label{font-size:.82rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}.chart-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}.toolbar{display:flex;gap:.5rem;flex-wrap:wrap}.pill{display:inline-block;padding:.2rem .5rem;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:.78rem}
        label{display:block;font-weight:600;font-size:.9rem;margin-bottom:.35rem} input,textarea,select{width:100%;padding:.6rem .7rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.95rem}
        textarea{min-height:90px}
        .btn{background:var(--primary);color:#fff;border:none;border-radius:.5rem;padding:.55rem .85rem;cursor:pointer;font-size:.9rem;display:inline-block;text-decoration:none}
        .btn-secondary{background:#4b5563}.btn-danger{background:var(--danger)} .btn-success{background:var(--ok)}
        .flash{padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1rem}.flash.success{background:#dcfce7;color:#166534}
        .error{font-size:.8rem;color:#dc2626;margin-top:.25rem}.muted{color:var(--muted);font-size:.9rem}
        .table{width:100%;border-collapse:collapse}.table th,.table td{padding:.7rem;border-bottom:1px solid var(--border);text-align:left;vertical-align:middle}
        .table th{font-size:.8rem;text-transform:uppercase;letter-spacing:.04em;color:var(--muted)}
        .status{display:inline-block;padding:.2rem .55rem;border-radius:999px;font-size:.78rem;font-weight:600}
        .status-pending{background:#ede9fe;color:#5b21b6}.status-confirmed{background:#dbeafe;color:#1d4ed8}.status-completed{background:#dcfce7;color:#166534}.status-cancelled{background:#fee2e2;color:#b91c1c}.status-no-show{background:#e5e7eb;color:#374151}
        @media (max-width: 1100px){.kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.chart-grid{grid-template-columns:1fr}}@media (max-width: 900px){.sidebar{display:none}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">Skin by Noor</div>
        <nav class="nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Website Settings</a>
            <a href="{{ route('admin.ai-settings.edit') }}" class="{{ request()->routeIs('admin.ai-settings.*') ? 'active' : '' }}">AI Settings</a>
            <a href="{{ route('admin.consultations.index') }}" class="{{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">Consultations</a>
            <a href="{{ route('admin.ai-content-helper.index') }}" class="{{ request()->routeIs('admin.ai-content-helper.*') ? 'active' : '' }}">AI Content Helper</a>
            <a href="{{ route('admin.homepage.index') }}" class="{{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">Homepage</a>
            <a href="{{ route('admin.about.edit') }}" class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}">About</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
            <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">Appointments</a>
            <a href="{{ route('admin.reports.overview') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Reports</a>
            <a href="{{ route('admin.calendar.index') }}" class="{{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">Calendar</a>
            <a href="{{ route('admin.availability.edit') }}" class="{{ request()->routeIs('admin.availability.*') ? 'active' : '' }}">Availability</a>
            <a href="{{ route('admin.blocked-dates.index') }}" class="{{ request()->routeIs('admin.blocked-dates.*') ? 'active' : '' }}">Blocked Dates</a>
            <a href="{{ route('admin.blocked-times.index') }}" class="{{ request()->routeIs('admin.blocked-times.*') ? 'active' : '' }}">Blocked Times</a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery</a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a>
            <a href="{{ route('admin.faq.index') }}" class="{{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">FAQ</a>
            <a href="{{ route('admin.policies.index') }}" class="{{ request()->routeIs('admin.policies.*') ? 'active' : '' }}">Policies</a>
            <a href="{{ route('admin.whatsapp.settings.edit') }}" class="{{ request()->routeIs('admin.whatsapp.settings.*') ? 'active' : '' }}">WhatsApp Settings</a>
            <a href="{{ route('admin.whatsapp.templates.index') }}" class="{{ request()->routeIs('admin.whatsapp.templates.*') ? 'active' : '' }}">WhatsApp Templates</a>
            <a href="{{ route('admin.whatsapp.logs.index') }}" class="{{ request()->routeIs('admin.whatsapp.logs.*') ? 'active' : '' }}">WhatsApp Logs</a>
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
@stack('scripts')
</body>
</html>
