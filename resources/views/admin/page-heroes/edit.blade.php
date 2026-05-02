@extends('admin.layouts.app')
@section('title','Edit Page Hero')
@section('content')@include('admin.page-heroes.form',['action'=>route('admin.page-heroes.update',$hero),'method'=>'PUT'])@endsection
