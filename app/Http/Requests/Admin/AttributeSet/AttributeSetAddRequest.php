<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;

class AttributeSetAddRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_create');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
