@extends('admin.layouts.app') @section('title','Edit FAQ') @section('header','Edit FAQ')
@section('content')<form method="POST" action="{{ route('admin.faq.update',$item) }}">@csrf @method('PUT')<div class="card">@include('admin.faq._form')</div><button class="btn">Save</button></form>@endsection
