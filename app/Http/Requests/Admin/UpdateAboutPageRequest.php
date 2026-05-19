<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class UpdateAboutPageRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title_fr' => ['required','string','max:255'],'title_en' => ['required','string','max:255'],'intro_fr' => ['nullable','string'],'intro_en' => ['nullable','string'],
            'story_fr' => ['nullable','string'],'story_en' => ['nullable','string'],'philosophy_fr' => ['nullable','string'],'philosophy_en' => ['nullable','string'],
            'qualifications_fr' => ['nullable','string'],'qualifications_en' => ['nullable','string'],'meta_title_fr' => ['nullable','string','max:255'],'meta_title_en' => ['nullable','string','max:255'],
            'meta_description_fr' => ['nullable','string'],'meta_description_en' => ['nullable','string'],'image' => ['nullable','file','mimes:png,jpg,jpeg,webp,svg','max:4096'],'is_published' => ['nullable','boolean'],
        ];
    }
}
