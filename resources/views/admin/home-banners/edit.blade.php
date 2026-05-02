@extends('admin.layouts.app')
@section('title','Edit Home Banner')
@section('content')@include('admin.home-banners.form',['action'=>route('admin.home-banners.update',$slide),'method'=>'PUT'])@endsection
