<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;

class AttributeSetListRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
