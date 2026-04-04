@extends('admin.layouts.app')

@section('title', 'Edit Admin User')
@section('header', 'Edit Admin User')

@section('content')
    <div class="card">
        <h2 style="margin-bottom: 1rem;">Update {{ $adminUser->name }}</h2>
        <form method="POST" action="{{ route('admin.users.update', $adminUser) }}">
            @method('PUT')
            @include('admin.users._form')
        </form>
    </div>
@endsection
