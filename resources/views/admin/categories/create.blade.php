@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('header', 'Create Category')

@section('content')
    <div class="card">
        <h2>Create Service Category</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            @include('admin.categories._form')
        </form>
    </div>
@endsection
