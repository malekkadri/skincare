@extends('admin.layouts.app')
@section('title', 'Blocked Dates')
@section('header', 'Blocked Dates')

@section('content')
<section class="card">
    <h2>Add Blocked Date</h2>
    <form method="POST" action="{{ route('admin.blocked-dates.store') }}" class="grid">
        @csrf
        <div><label>Date</label><input type="date" name="blocked_date" required></div>
        <div><label>Reason</label><input type="text" name="reason"></div>
        <div style="align-self:end"><button class="btn" type="submit">Add</button></div>
    </form>
</section>
<section class="card">
    <table class="table">
        <thead><tr><th>Date</th><th>Reason</th><th></th></tr></thead>
        <tbody>
            @forelse($blockedDates as $blockedDate)
                <tr>
                    <td>{{ $blockedDate->blocked_date->format('Y-m-d') }}</td>
                    <td>{{ $blockedDate->reason ?: '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.blocked-dates.destroy', $blockedDate) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No blocked dates.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $blockedDates->links() }}
</section>
@endsection
