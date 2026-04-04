@extends('admin.layouts.app')

@section('title', 'Admin Users')
@section('header', 'Admin Users')

@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem; gap: 1rem;">
            <div>
                <h2 style="margin-bottom:.25rem;">Admin access control</h2>
                <p class="muted" style="margin:0;">Manage admin accounts, roles, and active status.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn">Create Admin User</a>
        </div>

        @if($users->isEmpty())
            <p class="muted">No admin users found yet.</p>
        @else
            <div style="overflow:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $adminUser)
                            <tr>
                                <td>{{ $adminUser->name }}</td>
                                <td>{{ $adminUser->email }}</td>
                                <td><span class="pill">{{ $roles[$adminUser->role]['label'] ?? ucfirst(str_replace('_', ' ', $adminUser->role)) }}</span></td>
                                <td>
                                    <span class="status {{ $adminUser->is_active ? 'status-completed' : 'status-cancelled' }}">
                                        {{ $adminUser->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td><a href="{{ route('admin.users.edit', $adminUser) }}" class="btn btn-secondary">Edit</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1rem;">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
