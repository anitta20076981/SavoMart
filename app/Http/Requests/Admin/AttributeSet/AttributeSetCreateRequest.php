<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;

class AttributeSetCreateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_create');
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:attributes|max:255|min:3',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
        ];
    }
}
