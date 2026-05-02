@extends('admin.layouts.app')
@section('title','Create Page Hero')
@section('content')@include('admin.page-heroes.form',['action'=>route('admin.page-heroes.store'),'method'=>'POST'])@endsection
