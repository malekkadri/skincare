@extends('admin.layouts.app')

@section('title', 'Launch Readiness')
@section('header', 'Launch Readiness')

@section('content')
    <div class="card">
        <h2>Readiness Summary</h2>
        <p><strong>{{ $summary['passed'] }}/{{ $summary['total'] }}</strong> checks passed.</p>
        <p class="muted">Use this page for final staging and pre-launch verification.</p>
    </div>

    <div class="card">
        <h2>Checklist</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Check</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody>
            @foreach($checks as $check)
                <tr>
                    <td>{{ $check['label'] }}</td>
                    <td>
                        <span class="status {{ $check['status'] === 'ok' ? 'status-completed' : 'status-pending' }}">
                            {{ strtoupper($check['status']) }}
                        </span>
                    </td>
                    <td>{{ $check['message'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
