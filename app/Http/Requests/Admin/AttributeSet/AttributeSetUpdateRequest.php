<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class AttributeSetUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'required|max:255|min:3',
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
