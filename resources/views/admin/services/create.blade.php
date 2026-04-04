@extends('admin.layouts.app')

@section('title', 'Create Service')
@section('header', 'Create Service')

@section('content')
    <div class="card">
        <h2>Create Service</h2>
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.services._form')
        </form>
    </div>
@endsection
