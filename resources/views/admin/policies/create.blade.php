@extends('admin.layouts.app') @section('title','Create Policy') @section('header','Create Policy')
@section('content')<form method="POST" action="{{ route('admin.policies.store') }}">@csrf<div class="card">@include('admin.policies._form')</div><button class="btn">Create</button></form>@endsection
