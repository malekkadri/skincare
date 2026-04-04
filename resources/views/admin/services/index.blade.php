@extends('admin.layouts.app')

@section('title', 'Services')
@section('header', 'Services')

@section('content')
    <div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;">
        <div>
            <h2 style="margin:0;">Services</h2>
            <p class="muted" style="margin:.4rem 0 0;">Manage pricing, duration, status, and featured items.</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn" style="text-decoration:none;">Add Service</a>
    </div>

    <div class="card">
        <form method="GET" class="grid" style="margin-bottom:1rem;">
            <div>
                <label for="search">Search</label>
                <input id="search" name="search" value="{{ request('search') }}" placeholder="Name or slug">
            </div>
            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;gap:.5rem;">
                <button type="submit" class="btn">Filter</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary" style="text-decoration:none;">Reset</a>
            </div>
        </form>

        <div style="overflow:auto;">
            <table class="table">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Name (FR / EN)</th>
                    <th>Category</th>
                    <th>Price TND</th>
                    <th>Price EUR</th>
                    <th>Duration</th>
                    <th>Active</th>
                    <th>Featured</th>
                    <th>Sort</th>
                    <th style="width:200px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>
                            @if($service->image_url)
                                <img src="{{ $service->image_url }}" alt="{{ $service->name_en }}" style="width:52px;height:52px;object-fit:cover;border-radius:.5rem;">
                            @else
                                <span class="muted">No image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $service->name_fr }}</strong><br>
                            <span class="muted">{{ $service->name_en }}</span>
                        </td>
                        <td>{{ $service->category?->name_en ?? '—' }}</td>
                        <td>{{ number_format((float) $service->price_tnd, 2) }}</td>
                        <td>{{ number_format((float) $service->price_eur, 2) }}</td>
                        <td>{{ $service->duration_minutes }} min</td>
                        <td>{{ $service->is_active ? 'Yes' : 'No' }}</td>
                        <td>{{ $service->is_featured ? 'Yes' : 'No' }}</td>
                        <td>{{ $service->sort_order }}</td>
                        <td>
                            <div style="display:flex;gap:.5rem;">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-secondary" style="text-decoration:none;">Edit</a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="muted" style="text-align:center;">No services found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1rem;">{{ $services->links() }}</div>
    </div>
@endsection
