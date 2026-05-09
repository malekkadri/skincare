@extends('admin.layouts.app')

@section('title', 'Patient records')
@section('header', 'Patient records')

@section('content')
<section class="card">
    <form method="GET" class="toolbar" style="margin-bottom:1rem;align-items:flex-end;">
        <div style="min-width:320px;">
            <label for="q">Search patient</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Name, phone, email...">
        </div>
        <button class="btn" type="submit">Search</button>
        @if(request()->filled('q'))
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    <table class="table">
        <thead>
        <tr>
            <th>Patient</th>
            <th>Phone</th>
            <th>Appointments</th>
            <th>Consultations</th>
            <th>Progress Photos</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                <td>
                    <strong>{{ $client->full_name }}</strong><br>
                    <span class="muted">{{ $client->email ?: 'No email' }}</span>
                </td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->appointments_count }}</td>
                <td>{{ $client->consultations_count }}</td>
                <td>{{ $client->progress_photos_count }}</td>
                <td><a class="btn" href="{{ route('admin.clients.show', $client) }}">Open record</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No patients found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem;">{{ $clients->links() }}</div>
</section>
@endsection
