<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class StoreTestimonialRequest extends FormRequest
{ public function authorize(): bool { return true; }
  public function rules(): array { return ['client_name'=>['required','string','max:255'],'title_fr'=>['nullable','string','max:255'],'title_en'=>['nullable','string','max:255'],'content_fr'=>['required','string'],'content_en'=>['required','string'],'rating'=>['required','integer','between:1,5'],'service_id'=>['nullable','integer','exists:services,id'],'is_featured'=>['nullable','boolean'],'is_active'=>['nullable','boolean'],'sort_order'=>['nullable','integer']]; }
}
