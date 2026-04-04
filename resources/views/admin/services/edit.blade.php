@extends('admin.layouts.app')

@section('title', 'Edit Service')
@section('header', 'Edit Service')

@section('content')
    <div class="card">
        <h2>Edit Service</h2>
        <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.services._form')
        </form>
    </div>
@endsection
