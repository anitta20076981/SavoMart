<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerListDataRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
