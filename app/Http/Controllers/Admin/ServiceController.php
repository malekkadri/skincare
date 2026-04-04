<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();
        $categoryId = $request->integer('category_id');

        $services = Service::query()
            ->with('category')
            ->when($search, function ($query, $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('name_fr', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->orderBy('sort_order')
            ->orderBy('name_en')
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'categories' => ServiceCategory::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create', [
            'service' => new Service(),
            'categories' => ServiceCategory::query()->ordered()->get(),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name_en']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['buffer_minutes'] = $validated['buffer_minutes'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        unset($validated['image']);

        Service::query()->create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service,
            'categories' => ServiceCategory::query()->ordered()->get(),
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name_en']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['buffer_minutes'] = $validated['buffer_minutes'] ?? 0;

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                Storage::disk('public')->delete($service->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        unset($validated['image']);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->image_path) {
            Storage::disk('public')->delete($service->image_path);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
