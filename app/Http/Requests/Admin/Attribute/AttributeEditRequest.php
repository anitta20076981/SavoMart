<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeEditRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
