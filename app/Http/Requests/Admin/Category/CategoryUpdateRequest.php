<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('categories_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required|max:50|min:2',
            'name_ar' => 'required|max:50|min:2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name_ar.required' => ':attribute is required',
            'name.max' => ':attribute must be maximum of 50 character',
            'name.min' => ':attribute must be minimum of 2 character',

        ];
    }

    public function attributes()
    {
        return [
            'name' => 'English Name',
            'name_ar' => 'Arabic Name',
        ];
    }
}