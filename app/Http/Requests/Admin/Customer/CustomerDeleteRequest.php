<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_delete');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
