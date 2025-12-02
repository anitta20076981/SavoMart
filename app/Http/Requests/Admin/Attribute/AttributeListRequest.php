<?php

namespace App\Http\Requests\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class AttributeListRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
