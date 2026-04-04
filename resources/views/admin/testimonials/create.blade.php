@extends('admin.layouts.app') @section('title','Create Testimonial') @section('header','Create Testimonial')
@section('content')<form method="POST" action="{{ route('admin.testimonials.store') }}">@csrf<div class="card">@include('admin.testimonials._form')</div><button class="btn">Create</button></form>@endsection
