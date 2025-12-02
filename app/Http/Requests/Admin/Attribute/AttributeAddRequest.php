<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeAddRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_create');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
