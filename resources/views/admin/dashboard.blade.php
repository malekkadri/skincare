@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Business Dashboard')

@section('content')
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

    <div class="card">
        <h2>Automation Status</h2>
        <p><span class="pill">{{ $settings->whatsapp_automation_enabled ? 'Enabled' : 'Disabled' }}</span></p>
        <p class="muted">Pause until: {{ $settings->automation_pause_until?->timezone('Africa/Tunis')->format('Y-m-d H:i') ?? 'Not paused' }}</p>
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
            data: {labels: appointmentsByDay.map(i => i.day), datasets: [{label: 'Appointments', data: appointmentsByDay.map(i => i.total), borderColor: '#7c3aed', tension: .3}]}
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
            options: {indexAxis: 'y'}
        });

        new Chart(document.getElementById('statusDistribution'), {
            type: 'doughnut',
            data: {labels: Object.keys(statusDistribution), datasets: [{data: Object.values(statusDistribution), backgroundColor: ['#a78bfa','#93c5fd','#86efac','#fca5a5','#d1d5db']}]}
        });
    </script>
@endpush
