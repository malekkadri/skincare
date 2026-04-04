<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('slug') && $this->filled('name_en')) {
            $this->merge(['slug' => Str::slug((string) $this->input('name_en'))]);
        }
    }

    public function rules(): array
    {
        /** @var ServiceCategory $category */
        $category = $this->route('category');

        return [
            'name_fr' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('service_categories', 'slug')->ignore($category->id)],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
