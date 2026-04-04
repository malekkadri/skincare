@extends('admin.layouts.app')
@section('title', 'Revenue Report')
@section('header', 'Revenue Report')
@section('content')
@include('admin.reports.partials.nav')
@include('admin.reports.partials.filters')
<div class="card"><p class="muted">{{ $summary['assumption'] }}</p></div>
<div class="card"><h2>Totals by Currency</h2><table class="table"><thead><tr><th>Currency</th><th>Total Revenue</th><th>Average Booking</th><th>Completed Count</th></tr></thead><tbody>@foreach($summary['totals_by_currency'] as $item)<tr><td>{{ $item->booked_currency }}</td><td>{{ number_format($item->total,2) }}</td><td>{{ number_format($item->avg_value,2) }}</td><td>{{ $item->count }}</td></tr>@endforeach</tbody></table></div>
<div class="card"><h2>Top Revenue Services</h2><table class="table"><thead><tr><th>Service</th><th>Currency</th><th>Total</th></tr></thead><tbody>@foreach($summary['top_revenue_services'] as $item)<tr><td>{{ $item->service_name }}</td><td>{{ $item->booked_currency }}</td><td>{{ number_format($item->total,2) }}</td></tr>@endforeach</tbody></table></div>
@endsection
