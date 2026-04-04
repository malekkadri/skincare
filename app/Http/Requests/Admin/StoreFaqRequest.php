<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class StoreFaqRequest extends FormRequest
{ public function authorize(): bool { return true; }
  public function rules(): array { return ['question_fr'=>['required','string','max:255'],'question_en'=>['required','string','max:255'],'answer_fr'=>['required','string'],'answer_en'=>['required','string'],'category'=>['nullable','string','max:255'],'is_active'=>['nullable','boolean'],'sort_order'=>['nullable','integer']]; }
}
