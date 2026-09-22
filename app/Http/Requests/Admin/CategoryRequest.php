<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CategoryRequest extends FormRequest {public function authorize(){return $this->user()?->isAdmin()??false;}public function rules(){return ['name'=>'required|string|max:191','slug'=>['required','alpha_dash','max:191',Rule::unique('categories','slug')->ignore($this->route('category'))],'parent_id'=>['nullable','exists:categories,id',Rule::notIn([$this->route('category')?->id])],'tagline'=>'nullable|max:191','heading'=>'nullable|max:191','description'=>'nullable|string','image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:4096','banner_image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:6144','sort_order'=>'required|integer|min:0','is_active'=>'nullable|boolean','show_in_menu'=>'nullable|boolean','meta_title'=>'nullable|max:70','meta_description'=>'nullable|max:170'];}}
