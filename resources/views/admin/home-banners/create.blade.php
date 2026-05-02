@extends('admin.layouts.app')
@section('title','Create Home Banner')
@section('content')@include('admin.home-banners.form',['action'=>route('admin.home-banners.store'),'method'=>'POST'])@endsection
