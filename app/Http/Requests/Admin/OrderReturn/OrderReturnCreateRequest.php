<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnCreateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_returns_create');
    }

    public function rules()
    {
        return [
            'reason' => 'required',
            'location' => 'required',
            // 'products.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => ':attribute is required',
            'location.required' => ':attribute is required',
            'total_price.required' => ':attribute is required',
            // 'products.*.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'reason' => 'Reason',
            'location' => 'Location',
            'total_price' => 'At least one product is required',
            // 'products.*' => 'Products',
        ];
    }
}
