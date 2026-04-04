@extends('admin.layouts.app') @section('title','Edit Testimonial') @section('header','Edit Testimonial')
@section('content')<form method="POST" action="{{ route('admin.testimonials.update',$item) }}">@csrf @method('PUT')<div class="card">@include('admin.testimonials._form')</div><button class="btn">Save</button></form>@endsection
