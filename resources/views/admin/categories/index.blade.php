@extends('admin.layouts.app')

@section('title', 'Categories')
@section('header', 'Service Categories')

@section('content')
    <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;">
        <div>
            <h2 style="margin:0;">Categories</h2>
            <p class="muted" style="margin:.4rem 0 0;">Manage bilingual service categories and their display order.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn" style="text-decoration:none;">Add Category</a>
    </div>

    <div class="card" style="overflow:auto;">
        <table class="table">
            <thead>
            <tr>
                <th>Name (FR / EN)</th>
                <th>Slug</th>
                <th>Active</th>
                <th>Sort</th>
                <th>Services</th>
                <th style="width:200px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>
                        <strong>{{ $category->name_fr }}</strong><br>
                        <span class="muted">{{ $category->name_en }}</span>
                    </td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>{{ $category->is_active ? 'Yes' : 'No' }}</td>
                    <td>{{ $category->sort_order }}</td>
                    <td>{{ $category->services_count }}</td>
                    <td>
                        <div style="display:flex;gap:.5rem;">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary" style="text-decoration:none;">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted" style="text-align:center;">No categories found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem;">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
