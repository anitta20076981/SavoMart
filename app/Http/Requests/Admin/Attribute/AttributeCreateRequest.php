<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeCreateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_create');
    }

    public function rules()
    {
        return [
            'code' => 'required|max:255|min:3|unique:attributes,code,NULL,id,deleted_at,NULL',
            'name' => 'required|max:255|min:3|unique:attributes,name,NULL,id,deleted_at,NULL',
            'name_ar' => 'required|max:255|min:3|unique:attributes,name,NULL,id,deleted_at,NULL',
            'input_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name_ar.required' => ':attribute is required',
            'code.required' => ':attribute is required',
            'input_type.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'English Name',
            'name_ar' => 'Arabic Name',
            'code' => 'Attribute Code',
            'input_type' => 'Input Type',
        ];
    }
}