@extends('admin.layouts.app') @section('title','Edit Gallery Item') @section('header','Edit Gallery Item')
@section('content')<form method="POST" enctype="multipart/form-data" action="{{ route('admin.gallery.update',$item) }}">@csrf @method('PUT') <div class="card">@include('admin.gallery._form')</div><button class="btn">Save</button></form>@endsection
