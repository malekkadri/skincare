<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceCategoryRequest;
use App\Http\Requests\Admin\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => ServiceCategory::query()->withCount('services')->ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new ServiceCategory(),
        ]);
    }

    public function store(StoreServiceCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name_en']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceCategory::query()->create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ServiceCategory $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $category): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name_en']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ServiceCategory $category): RedirectResponse
    {
        if ($category->services()->exists()) {
            return back()->with('success', 'Category cannot be deleted while services are linked to it.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
