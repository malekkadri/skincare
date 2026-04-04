@extends('admin.layouts.app')
@section('title', 'Services Performance Report')
@section('header', 'Services Performance')
@section('content')
@include('admin.reports.partials.nav')
@include('admin.reports.partials.filters')
<div class="card"><h2>Service Performance Table</h2><table class="table"><thead><tr><th>Service</th><th>Total</th><th>Completed</th><th>Cancelled</th><th>No Show</th><th>Revenue TND</th><th>Revenue EUR</th><th>Avg Value</th><th>Featured</th><th>Last Booked</th></tr></thead><tbody>@foreach($summary['services'] as $row)<tr><td>{{ $row->name_en }}</td><td>{{ $row->total_bookings }}</td><td>{{ $row->completed_bookings }}</td><td>{{ $row->cancellations }}</td><td>{{ $row->no_shows }}</td><td>{{ number_format($row->revenue_tnd,2) }}</td><td>{{ number_format($row->revenue_eur,2) }}</td><td>{{ number_format((float) $row->avg_booking_value,2) }}</td><td>{{ $row->is_featured ? 'Yes' : 'No' }}</td><td>{{ $row->last_booked_date }}</td></tr>@endforeach</tbody></table></div>
@endsection
