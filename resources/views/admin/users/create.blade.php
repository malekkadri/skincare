@extends('admin.layouts.app')

@section('title', 'Create Admin User')
@section('header', 'Create Admin User')

@section('content')
    <div class="card">
        <h2 style="margin-bottom: 1rem;">New Admin User</h2>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @include('admin.users._form')
        </form>
    </div>
@endsection
