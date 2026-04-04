@extends('admin.layouts.app') @section('title','Create Gallery Item') @section('header','Create Gallery Item')
@section('content')<form method="POST" enctype="multipart/form-data" action="{{ route('admin.gallery.store') }}">@csrf <div class="card">@include('admin.gallery._form')</div><button class="btn">Create</button></form>@endsection
