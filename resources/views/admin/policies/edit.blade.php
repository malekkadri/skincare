@extends('admin.layouts.app') @section('title','Edit Policy') @section('header','Edit Policy')
@section('content')<form method="POST" action="{{ route('admin.policies.update',$item) }}">@csrf @method('PUT')<div class="card">@include('admin.policies._form')</div><button class="btn">Save</button></form>@endsection
