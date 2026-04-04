<div class="toolbar" style="margin-bottom:1rem">
    <a class="btn {{ request()->routeIs('admin.reports.overview') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.overview', request()->query()) }}">Overview</a>
    <a class="btn {{ request()->routeIs('admin.reports.appointments') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.appointments', request()->query()) }}">Appointments</a>
    <a class="btn {{ request()->routeIs('admin.reports.revenue') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.revenue', request()->query()) }}">Revenue</a>
    <a class="btn {{ request()->routeIs('admin.reports.services') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.services', request()->query()) }}">Services</a>
    <a class="btn {{ request()->routeIs('admin.reports.consultations') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.consultations', request()->query()) }}">Consultations</a>
    <a class="btn {{ request()->routeIs('admin.reports.whatsapp') ? '' : 'btn-secondary' }}" href="{{ route('admin.reports.whatsapp', request()->query()) }}">WhatsApp</a>
</div>
