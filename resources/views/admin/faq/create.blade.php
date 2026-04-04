@extends('admin.layouts.app') @section('title','Create FAQ') @section('header','Create FAQ')
@section('content')<form method="POST" action="{{ route('admin.faq.store') }}">@csrf<div class="card">@include('admin.faq._form')</div><button class="btn">Create</button></form>@endsection
