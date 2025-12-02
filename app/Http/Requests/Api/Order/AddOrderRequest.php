<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiRequest;

class AddOrderRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cart_id' => 'required',
            'address_id' => 'required',
            'payment_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => ':attribute is required',
            'address_id.required' => ':attribute is required',
            'payment_type.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'order_id' => 'Order id',
            'order_idaddress_id' => 'address id',
            'payment_type' => 'payment type',
        ];
    }
}
