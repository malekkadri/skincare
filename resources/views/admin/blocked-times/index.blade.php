@extends('admin.layouts.app')
@section('title', 'Blocked Time Ranges')
@section('header', 'Blocked Time Ranges')

@section('content')
<section class="card">
    <h2>Add Blocked Time Range</h2>
    <form method="POST" action="{{ route('admin.blocked-times.store') }}" class="grid">
        @csrf
        <div><label>Date</label><input type="date" name="blocked_date" required></div>
        <div><label>Start</label><input type="time" name="start_time" required></div>
        <div><label>End</label><input type="time" name="end_time" required></div>
        <div><label>Reason</label><input type="text" name="reason"></div>
        <div style="align-self:end"><button class="btn" type="submit">Add</button></div>
    </form>
</section>
<section class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Start</th><th>End</th><th>Reason</th><th></th></tr></thead>
        <tbody>
            @forelse($blockedTimes as $blockedTime)
                <tr>
                    <td>{{ $blockedTime->blocked_date->format('Y-m-d') }}</td>
                    <td>{{ \Illuminate\Support\Str::substr($blockedTime->start_time,0,5) }}</td>
                    <td>{{ \Illuminate\Support\Str::substr($blockedTime->end_time,0,5) }}</td>
                    <td>{{ $blockedTime->reason ?: '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.blocked-times.destroy', $blockedTime) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No blocked time ranges.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $blockedTimes->links() }}
</section>
@endsection
