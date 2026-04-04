@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('header', 'Edit Category')

@section('content')
    <div class="card">
        <h2>Edit Service Category</h2>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>
@endsection
