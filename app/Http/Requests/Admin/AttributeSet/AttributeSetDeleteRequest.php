<?php

namespace App\Http\Requests\Admin\AttributeSet;

use Illuminate\Foundation\Http\FormRequest;

class AttributeSetDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('attribute_set_delete');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
