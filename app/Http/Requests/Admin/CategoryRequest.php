<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->isAdmin()
            ?? false;
    }

    public function rules()
    {
        $category =
            $this->route('category');

        return [

            'name' => [
                'required',
                'string',
                'max:191',
            ],

            'slug' => [
                'required',
                'alpha_dash',
                'max:191',

                Rule::unique(
                    'categories',
                    'slug'
                )->ignore(
                    $category?->id
                ),
            ],

            'parent_id' => [
                'nullable',

                Rule::exists(
                    'categories',
                    'id'
                )->where(
                    function ($query) {
                        $query->whereNull(
                            'parent_id'
                        );
                    }
                ),

                Rule::notIn([
                    $category?->id
                ]),
            ],

            'tagline' => [
                'nullable',
                'string',
                'max:191',
            ],

            'heading' => [
                'nullable',
                'string',
                'max:191',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:6144',
            ],

            'remove_image' => [
                'nullable',
                'boolean',
            ],

            'remove_banner_image' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'show_in_menu' => [
                'nullable',
                'boolean',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:70',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:170',
            ],

        ];
    }
}