<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class StorePolicyRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    protected function prepareForValidation(): void { if (! $this->filled('slug') && $this->filled('title_en')) { $this->merge(['slug' => Str::slug((string) $this->input('title_en'))]); } }
    public function rules(): array { return ['title_fr'=>['required','string','max:255'],'title_en'=>['required','string','max:255'],'slug'=>['required','alpha_dash','max:255',Rule::unique('policies','slug')],'content_fr'=>['required','string'],'content_en'=>['required','string'],'meta_title_fr'=>['nullable','string','max:255'],'meta_title_en'=>['nullable','string','max:255'],'meta_description_fr'=>['nullable','string'],'meta_description_en'=>['nullable','string'],'is_active'=>['nullable','boolean'],'sort_order'=>['nullable','integer']]; }
}
