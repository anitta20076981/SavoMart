<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_create');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
