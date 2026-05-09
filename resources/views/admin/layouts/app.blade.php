<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Skin by Noor</title>
    <style>
        :root {
            --bg: #f3f5fb;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --primary: #7c3aed;
            --primary-soft: #ede9fe;
            --border: #e5e7eb;
            --danger: #dc2626;
            --ok: #166534;
            --sidebar: #0f172a;
            --sidebar-soft: #1e293b;
            --sidebar-text: #e2e8f0;
            --sidebar-muted: #94a3b8;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .layout { display: flex; min-height: 100vh; }

        .sidebar {
            width: 288px;
            background: linear-gradient(180deg, #0b1222 0%, #0f172a 100%);
            color: var(--sidebar-text);
            padding: 1.3rem 1rem;
            border-right: 1px solid #1f2937;
            overflow-y: auto;
        }

        .brand-wrap {
            padding: .25rem .5rem .9rem;
            margin-bottom: .6rem;
            border-bottom: 1px solid #1f2937;
        }

        .brand { font-size: 1.1rem; font-weight: 800; letter-spacing: .02em; }
        .brand-sub { margin-top: .25rem; color: var(--sidebar-muted); font-size: .78rem; }

        .nav-section { margin-top: 1rem; }
        .section-title {
            margin: 0 0 .45rem;
            padding: 0 .5rem;
            color: var(--sidebar-muted);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .nav a {
            display: block;
            padding: .6rem .75rem;
            border-radius: .62rem;
            color: var(--sidebar-text);
            text-decoration: none;
            margin-bottom: .2rem;
            font-size: .92rem;
            border: 1px solid transparent;
            transition: .15s ease;
        }

        .nav a:hover {
            background: #111f38;
            border-color: #1f2f4e;
            transform: translateX(2px);
        }

        .nav a.active {
            background: #1a2844;
            border-color: #2b3f66;
            color: #fff;
            font-weight: 600;
        }

        .content { flex: 1; min-width: 0; }

        .topbar {
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            padding: .95rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar strong { font-size: 1rem; }

        .main { padding: 1.5rem; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: .9rem;
            padding: 1.15rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .04);
        }

        .card h2 { margin-top: 0; font-size: 1.05rem; }

        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .kpi-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1rem; }

        .kpi .value { font-size: 1.5rem; font-weight: 700; }

        .kpi .label {
            font-size: .79rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .45rem;
        }

        .chart-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .toolbar { display: flex; gap: .5rem; flex-wrap: wrap; }

        .pill {
            display: inline-block;
            padding: .2rem .55rem;
            border-radius: 999px;
            background: var(--primary-soft);
            color: #4338ca;
            font-size: .78rem;
            font-weight: 600;
        }

        label { display: block; font-weight: 600; font-size: .9rem; margin-bottom: .35rem; }

        input, textarea, select {
            width: 100%;
            padding: .6rem .7rem;
            border: 1px solid #d1d5db;
            border-radius: .5rem;
            font-size: .95rem;
            background: #fff;
        }

        textarea { min-height: 90px; }

        .btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: .56rem;
            padding: .55rem .9rem;
            cursor: pointer;
            font-size: .9rem;
            display: inline-block;
            text-decoration: none;
        }

        .btn-secondary { background: #4b5563; }
        .btn-danger { background: var(--danger); }
        .btn-success { background: var(--ok); }

        .flash { padding: .75rem 1rem; border-radius: .5rem; margin-bottom: 1rem; }
        .flash.success { background: #dcfce7; color: #166534; }
        .flash.error { background: #fee2e2; color: #991b1b; }

        .error { font-size: .8rem; color: #dc2626; margin-top: .25rem; }
        .muted { color: var(--muted); font-size: .9rem; }

        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: .7rem; border-bottom: 1px solid var(--border); text-align: left; vertical-align: middle; }

        .table th {
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
        }

        .status { display: inline-block; padding: .2rem .55rem; border-radius: 999px; font-size: .78rem; font-weight: 600; }
        .status-pending { background: #ede9fe; color: #5b21b6; }
        .status-confirmed { background: #dbeafe; color: #1d4ed8; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #b91c1c; }
        .status-no-show { background: #e5e7eb; color: #374151; }

        @media (max-width: 1200px) { .kpi-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 1024px) {
            .sidebar { display: none; }
            .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .chart-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 720px) {
            .main { padding: 1rem; }
            .topbar { padding: .85rem 1rem; }
            .grid { grid-template-columns: 1fr; }
            .kpi-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand-wrap">
            <div class="brand">Skin by Noor</div>
            <div class="brand-sub">Admin workspace</div>
        </div>

        @php($user = auth()->user())

        <nav class="nav">
            <div class="nav-section">
                <p class="section-title">Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                @if($user?->hasPermission('view_reports'))
                    <a href="{{ route('admin.reports.overview') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Reports</a>
                @endif
            </div>

            @if($user?->hasPermission('manage_appointments') || $user?->hasPermission('manage_consultations') || $user?->hasPermission('manage_availability'))
                <div class="nav-section">
                    <p class="section-title">Bookings & Calendar</p>
                    @if($user?->hasPermission('manage_appointments'))
                        <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">Appointments</a>
                        <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">🧾 {{ app()->getLocale() === 'fr' ? 'Fiches patients' : 'Patient records' }}</a>
                        <a href="{{ route('admin.calendar.index') }}" class="{{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">Calendar</a>
                    @endif
                    @if($user?->hasPermission('manage_consultations'))
                        <a href="{{ route('admin.consultations.index') }}" class="{{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">Consultations</a>
                    @endif
                    @if($user?->hasPermission('manage_availability'))
                        <a href="{{ route('admin.availability.edit') }}" class="{{ request()->routeIs('admin.availability.*') ? 'active' : '' }}">Availability</a>
                        <a href="{{ route('admin.blocked-dates.index') }}" class="{{ request()->routeIs('admin.blocked-dates.*') ? 'active' : '' }}">Blocked Dates</a>
                        <a href="{{ route('admin.blocked-times.index') }}" class="{{ request()->routeIs('admin.blocked-times.*') ? 'active' : '' }}">Blocked Times</a>
                    @endif
                </div>
            @endif

            @if($user?->hasPermission('manage_services') || $user?->hasPermission('manage_cms'))
                <div class="nav-section">
                    <p class="section-title">Content & Services</p>
                    @if($user?->hasPermission('manage_services'))
                        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
                    @endif
                    @if($user?->hasPermission('manage_cms'))
                        <a href="{{ route('admin.homepage.index') }}" class="{{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">Homepage</a>
                        <a href="{{ route('admin.about.edit') }}" class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}">About</a>
                        <a href="{{ route('admin.page-heroes.index') }}" class="{{ request()->routeIs('admin.page-heroes.*') ? 'active' : '' }}">Page Heroes</a>
                        <a href="{{ route('admin.home-banners.index') }}" class="{{ request()->routeIs('admin.home-banners.*') ? 'active' : '' }}">Home Banners</a>
                        <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery</a>
                        <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a>
                        <a href="{{ route('admin.faq.index') }}" class="{{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">FAQ</a>
                        <a href="{{ route('admin.policies.index') }}" class="{{ request()->routeIs('admin.policies.*') ? 'active' : '' }}">Policies</a>
                    @endif
                </div>
            @endif

            @if($user?->hasPermission('manage_whatsapp') || $user?->hasPermission('manage_ai'))
                <div class="nav-section">
                    <p class="section-title">Automation & AI</p>
                    @if($user?->hasPermission('manage_ai'))
                        <a href="{{ route('admin.ai-settings.edit') }}" class="{{ request()->routeIs('admin.ai-settings.*') ? 'active' : '' }}">AI Settings</a>
                        <a href="{{ route('admin.ai-content-helper.index') }}" class="{{ request()->routeIs('admin.ai-content-helper.*') ? 'active' : '' }}">AI Content Helper</a>
                    @endif
                    @if($user?->hasPermission('manage_whatsapp'))
                        <a href="{{ route('admin.whatsapp.settings.edit') }}" class="{{ request()->routeIs('admin.whatsapp.settings.*') ? 'active' : '' }}">WhatsApp Settings</a>
                        <a href="{{ route('admin.whatsapp.templates.index') }}" class="{{ request()->routeIs('admin.whatsapp.templates.*') ? 'active' : '' }}">WhatsApp Templates</a>
                        <a href="{{ route('admin.whatsapp.logs.index') }}" class="{{ request()->routeIs('admin.whatsapp.logs.*') ? 'active' : '' }}">WhatsApp Logs</a>
                    @endif
                </div>
            @endif

            @if($user?->hasPermission('manage_settings') || $user?->hasPermission('manage_admin_users') || $user?->hasPermission('view_system_health'))
                <div class="nav-section">
                    <p class="section-title">Administration</p>
                    @if($user?->hasPermission('manage_settings'))
                        <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Website Settings</a>
                    @endif
                    @if($user?->hasPermission('manage_admin_users'))
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Admin Users</a>
                    @endif
                    @if($user?->hasPermission('view_system_health'))
                        <a href="{{ route('admin.ops.health') }}" class="{{ request()->routeIs('admin.ops.health') ? 'active' : '' }}">System Health</a>
                        <a href="{{ route('admin.ops.launch-readiness') }}" class="{{ request()->routeIs('admin.ops.launch-readiness') ? 'active' : '' }}">Launch Readiness</a>
                    @endif
                </div>
            @endif
        </nav>
    </aside>

    <div class="content">
        <header class="topbar">
            <strong>@yield('header', 'Admin')</strong>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </header>

        <main class="main">
            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="flash error">Please review the form errors and try again.</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
