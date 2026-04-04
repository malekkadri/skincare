@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Business Dashboard')

@section('content')
    <div class="card" style="background:linear-gradient(135deg,#1e1b4b 0%,#3730a3 45%,#4f46e5 100%);color:#eef2ff;border:none;">
        <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <h2 style="margin-bottom:.4rem;color:#fff;">Welcome to your admin hub</h2>
                <p style="margin:0;max-width:760px;color:#dbeafe;">Monitor bookings, team operations, and automation health in one streamlined workspace.</p>
            </div>
            <div class="toolbar">
                <a href="{{ route('admin.appointments.create') }}" class="btn" style="background:#fff;color:#312e81;">+ New Appointment</a>
                @if(auth()->user()?->hasPermission('manage_services'))
                    <a href="{{ route('admin.services.create') }}" class="btn" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.35);">+ New Service</a>
                @endif
            </div>
        </div>
    </div>

    <div class="kpi-grid">
        @foreach([
            ['Appointments Today', $dashboard['kpis']['appointments_today']],
            ['Appointments This Week', $dashboard['kpis']['appointments_week']],
            ['Appointments This Month', $dashboard['kpis']['appointments_month']],
            ['Confirmed Upcoming', $dashboard['kpis']['confirmed_upcoming']],
            ['Completed This Month', $dashboard['kpis']['completed_month']],
            ['Cancelled This Month', $dashboard['kpis']['cancelled_month']],
            ['Consultations This Month', $dashboard['kpis']['consultations_month']],
            ['Pending Consultations', $dashboard['kpis']['consultations_pending']],
            ['WhatsApp Sent Today', $dashboard['kpis']['whatsapp_sent_today']],
            ['WhatsApp Failed Today', $dashboard['kpis']['whatsapp_failed_today']],
        ] as [$label, $value])
            <div class="card kpi">
                <div class="label">{{ $label }}</div>
                <div class="value">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid">
        <div class="card">
            <h2>Automation Status</h2>
            <p><span class="pill">{{ $settings->whatsapp_automation_enabled ? 'Enabled' : 'Disabled' }}</span></p>
            <p class="muted">Pause until: {{ $settings->automation_pause_until?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? 'Not paused' }}</p>
            @if(auth()->user()?->hasPermission('manage_whatsapp'))
                <a href="{{ route('admin.whatsapp.settings.edit') }}" class="btn" style="margin-top:.2rem;">Manage WhatsApp</a>
            @endif
        </div>

        <div class="card">
            <h2>Quick Actions</h2>
            <div class="toolbar">
                <a href="{{ route('admin.calendar.index') }}" class="btn btn-secondary">Open Calendar</a>
                <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary">View Consultations</a>
                @if(auth()->user()?->hasPermission('view_reports'))
                    <a href="{{ route('admin.reports.overview') }}" class="btn btn-secondary">Open Reports</a>
                @endif
            </div>
            <p class="muted" style="margin-top:.75rem;">Tip: use the grouped sidebar sections for faster navigation by workflow.</p>
        </div>
    </div>

    <div class="chart-grid">
        <div class="card"><h2>Appointments by Day (Last 30 Days)</h2><canvas id="appointmentsByDay"></canvas></div>
        <div class="card"><h2>Completed Revenue Trend (Last 30 Days)</h2><canvas id="revenueTrend"></canvas></div>
        <div class="card"><h2>Top Services</h2><canvas id="topServices"></canvas></div>
        <div class="card"><h2>Appointment Status Distribution</h2><canvas id="statusDistribution"></canvas></div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const appointmentsByDay = @json($dashboard['appointmentsByDay']);
        const revenueTrend = @json($dashboard['revenueTrend']);
        const topServices = @json($dashboard['topServices']);
        const statusDistribution = @json($dashboard['statusDistribution']);

        new Chart(document.getElementById('appointmentsByDay'), {
            type: 'line',
            data: {labels: appointmentsByDay.map(i => i.day), datasets: [{label: 'Appointments', data: appointmentsByDay.map(i => i.total), borderColor: '#7c3aed', tension: .3}]},
            options: {plugins: {legend: {display: false}}}
        });

        const revenueBuckets = [...new Set(revenueTrend.map(i => i.day))];
        const byCurrency = (currency) => revenueBuckets.map(day => {
            const found = revenueTrend.find(item => item.day === day && item.booked_currency === currency);
            return found ? Number(found.total) : 0;
        });

        new Chart(document.getElementById('revenueTrend'), {
            type: 'line',
            data: {labels: revenueBuckets, datasets: [
                {label: 'TND', data: byCurrency('TND'), borderColor: '#0f766e', tension: .25},
                {label: 'EUR', data: byCurrency('EUR'), borderColor: '#1d4ed8', tension: .25}
            ]}
        });

        new Chart(document.getElementById('topServices'), {
            type: 'bar',
            data: {labels: topServices.map(i => i.service_name), datasets: [{label: 'Bookings', data: topServices.map(i => i.total), backgroundColor: '#8b5cf6'}]},
            options: {indexAxis: 'y', plugins: {legend: {display: false}}}
        });

        new Chart(document.getElementById('statusDistribution'), {
            type: 'doughnut',
            data: {labels: Object.keys(statusDistribution), datasets: [{data: Object.values(statusDistribution), backgroundColor: ['#a78bfa','#93c5fd','#86efac','#fca5a5','#d1d5db']}]}
        });
    </script>
@endpush
