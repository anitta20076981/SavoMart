<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class AttributeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $data = $this->request->all();
        $attributeId = $data['id'];

        return [
            'code' => 'nullable|max:255|min:3|unique:attributes,code,' . $attributeId . ',id,deleted_at,NULL',
            'name' => 'nullable|max:255|min:3|unique:attributes,name,' . $attributeId . ',id,deleted_at,NULL',
            'name_ar' => 'nullable|max:255|min:3|unique:attributes,name,' . $attributeId . ',id,deleted_at,NULL',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name_ar.required' => ':attribute is required',
            'code.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'English Name',
            'name_ar' => 'Arabic Name',
            'code' => 'Attribute Code',
        ];
    }
}