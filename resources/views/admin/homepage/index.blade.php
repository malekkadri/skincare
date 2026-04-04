@extends('admin.layouts.app')
@section('title','Homepage') @section('header','Homepage Sections')
@section('content')<div class="card"><table class="table"><tr><th>Key</th><th>Title EN</th><th>Active</th><th></th></tr>@foreach($sections as $section)<tr><td>{{ $section->key }}</td><td>{{ $section->title_en }}</td><td>{{ $section->is_active ? 'Yes':'No' }}</td><td><a class="btn" href="{{ route('admin.homepage.edit',$section) }}">Edit</a></td></tr>@endforeach</table></div>@endsection
