<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;

class AttributeSetEditRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
