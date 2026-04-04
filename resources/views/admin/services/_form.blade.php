@php($isEdit = $service->exists)
<div class="grid">
    <div>
        <label for="category_id">Category</label>
        <select id="category_id" name="category_id">
            <option value="">Uncategorized</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $service->category_id) === (string) $category->id)>
                    {{ $category->name_en }} / {{ $category->name_fr }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="slug">Slug (auto-generated from EN name if left empty)</label>
        <input id="slug" name="slug" value="{{ old('slug', $service->slug) }}" placeholder="hydrating-facial">
        @error('slug') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="name_fr">Name (FR)</label>
        <input id="name_fr" name="name_fr" value="{{ old('name_fr', $service->name_fr) }}" required>
        @error('name_fr') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="name_en">Name (EN)</label>
        <input id="name_en" name="name_en" value="{{ old('name_en', $service->name_en) }}" required>
        @error('name_en') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="price_tnd">Price (TND)</label>
        <input id="price_tnd" type="number" step="0.01" min="0" name="price_tnd" value="{{ old('price_tnd', $service->price_tnd) }}" required>
        @error('price_tnd') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="price_eur">Price (EUR)</label>
        <input id="price_eur" type="number" step="0.01" min="0" name="price_eur" value="{{ old('price_eur', $service->price_eur) }}" required>
        @error('price_eur') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="duration_minutes">Duration (minutes)</label>
        <input id="duration_minutes" type="number" min="1" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes ?? 60) }}" required>
        @error('duration_minutes') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="buffer_minutes">Buffer (minutes)</label>
        <input id="buffer_minutes" type="number" min="0" name="buffer_minutes" value="{{ old('buffer_minutes', $service->buffer_minutes ?? 0) }}">
        @error('buffer_minutes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
        @error('sort_order') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div>
        <label for="image">Service Image</label>
        <input id="image" type="file" name="image" accept="image/*">
        @error('image') <div class="error">{{ $message }}</div> @enderror
        @if($service->image_url)
            <p class="muted">Current image:</p>
            <img src="{{ $service->image_url }}" alt="Current image" style="width:96px;height:96px;object-fit:cover;border-radius:.5rem;">
        @endif
    </div>
</div>

<div class="grid">
    <div>
        <label for="short_description_fr">Short Description (FR)</label>
        <textarea id="short_description_fr" name="short_description_fr">{{ old('short_description_fr', $service->short_description_fr) }}</textarea>
        @error('short_description_fr') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div>
        <label for="short_description_en">Short Description (EN)</label>
        <textarea id="short_description_en" name="short_description_en">{{ old('short_description_en', $service->short_description_en) }}</textarea>
        @error('short_description_en') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="grid">
    <div>
        <label for="description_fr">Description (FR)</label>
        <textarea id="description_fr" name="description_fr">{{ old('description_fr', $service->description_fr) }}</textarea>
        @error('description_fr') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div>
        <label for="description_en">Description (EN)</label>
        <textarea id="description_en" name="description_en">{{ old('description_en', $service->description_en) }}</textarea>
        @error('description_en') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div style="display:flex;gap:1.5rem;">
    <label style="display:flex;align-items:center;gap:.5rem;">
        <input type="checkbox" name="is_active" value="1" style="width:auto;" @checked(old('is_active', $isEdit ? $service->is_active : true))>
        Active service
    </label>

    <label style="display:flex;align-items:center;gap:.5rem;">
        <input type="checkbox" name="is_featured" value="1" style="width:auto;" @checked(old('is_featured', $service->is_featured))>
        Featured service
    </label>
</div>
@error('is_active') <div class="error">{{ $message }}</div> @enderror
@error('is_featured') <div class="error">{{ $message }}</div> @enderror

<div style="margin-top:1rem;display:flex;gap:.5rem;">
    <button type="submit" class="btn">{{ $isEdit ? 'Update Service' : 'Create Service' }}</button>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary" style="text-decoration:none;">Cancel</a>
</div>
