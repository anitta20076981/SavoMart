<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEditRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
