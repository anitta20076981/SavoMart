<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_delete');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
