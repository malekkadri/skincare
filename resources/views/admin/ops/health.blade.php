@extends('admin.layouts.app')

@section('title', 'System Health')
@section('header', 'System Health')

@section('content')
    <div class="grid">
        <div class="card">
            <h2>Runtime Checks</h2>
            <table class="table">
                <tbody>
                @foreach($checks as $label => $value)
                    <tr>
                        <th>{{ str_replace('_', ' ', ucfirst($label)) }}</th>
                        <td>{{ $value ?: 'n/a' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Manual Backup Trigger</h2>
            <form method="POST" action="{{ route('admin.ops.backups.store') }}">
                @csrf
                <label>Backup prefix</label>
                <input type="text" name="prefix" value="manual" maxlength="40">
                @error('prefix')<div class="error">{{ $message }}</div>@enderror
                <button class="btn" type="submit" style="margin-top: .75rem;">Create Backup Artifact</button>
            </form>
            <p class="muted" style="margin-top:1rem;">Artifacts are stored under <code>storage/app/backups</code>.</p>
        </div>
    </div>

    <div class="card">
        <h2>Recent Backup Artifacts</h2>
        @if(empty($backups))
            <p class="muted">No backup artifacts yet.</p>
        @else
            <div style="overflow:auto;">
                <table class="table">
                    <thead><tr><th>Path</th><th>Size</th><th>Modified</th></tr></thead>
                    <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td>{{ $backup['path'] }}</td>
                            <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                            <td>{{ $backup['modified_at'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
