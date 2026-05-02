@extends('admin.layouts.app')
@section('title','Page Heroes')
@section('content')
<div class="card"><a class="btn" href="{{ route('admin.page-heroes.create') }}">New Hero</a></div>
<div class="card"><table class="table"><tr><th>Page</th><th>Title EN</th><th>Active</th><th></th></tr>@foreach($heroes as $hero)<tr><td>{{ $hero->page_key }}</td><td>{{ $hero->title_en }}</td><td>{{ $hero->is_active?'Yes':'No' }}</td><td><a class="btn btn-secondary" href="{{ route('admin.page-heroes.edit',$hero) }}">Edit</a><form method="POST" action="{{ route('admin.page-heroes.destroy',$hero) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger">Delete</button></form></td></tr>@endforeach</table></div>
@endsection
