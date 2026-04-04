@php($isEdit = $category->exists)
<div class="grid">
    <div>
        <label for="name_fr">Name (FR)</label>
        <input id="name_fr" name="name_fr" value="{{ old('name_fr', $category->name_fr) }}" required>
        @error('name_fr') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="name_en">Name (EN)</label>
        <input id="name_en" name="name_en" value="{{ old('name_en', $category->name_en) }}" required>
        @error('name_en') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="slug">Slug (auto-generated from EN name if left empty)</label>
        <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="hydrafacial">
        @error('slug') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        @error('sort_order') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="description_fr">Description (FR)</label>
        <textarea id="description_fr" name="description_fr">{{ old('description_fr', $category->description_fr) }}</textarea>
        @error('description_fr') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="description_en">Description (EN)</label>
        <textarea id="description_en" name="description_en">{{ old('description_en', $category->description_en) }}</textarea>
        @error('description_en') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<label style="display:flex;align-items:center;gap:.5rem;">
    <input type="checkbox" name="is_active" value="1" style="width:auto;" @checked(old('is_active', $isEdit ? $category->is_active : true))>
    Active category
</label>
@error('is_active') <div class="error">{{ $message }}</div> @enderror

<div style="margin-top:1rem;display:flex;gap:.5rem;">
    <button type="submit" class="btn">{{ $isEdit ? 'Update Category' : 'Create Category' }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="text-decoration:none;">Cancel</a>
</div>
