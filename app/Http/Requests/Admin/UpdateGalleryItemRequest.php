<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class UpdateGalleryItemRequest extends FormRequest
{ public function authorize(): bool { return true; }
  public function rules(): array { return ['title_fr'=>['nullable','string','max:255'],'title_en'=>['nullable','string','max:255'],'caption_fr'=>['nullable','string'],'caption_en'=>['nullable','string'],'image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],'category'=>['nullable','string','max:255'],'is_before_after'=>['nullable','boolean'],'is_featured'=>['nullable','boolean'],'is_active'=>['nullable','boolean'],'sort_order'=>['nullable','integer']]; }
}
