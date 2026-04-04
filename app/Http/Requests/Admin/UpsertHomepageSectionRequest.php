<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class UpsertHomepageSectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title_fr' => ['nullable','string','max:255'],'title_en' => ['nullable','string','max:255'],
            'content_fr' => ['nullable','string'],'content_en' => ['nullable','string'],
            'button_text_fr' => ['nullable','string','max:255'],'button_text_en' => ['nullable','string','max:255'],'button_url' => ['nullable','string','max:255'],
            'secondary_button_text_fr' => ['nullable','string','max:255'],'secondary_button_text_en' => ['nullable','string','max:255'],'secondary_button_url' => ['nullable','string','max:255'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:3072'],'is_active' => ['nullable','boolean'],'sort_order' => ['nullable','integer'],
        ];
    }
}
