<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiRequest;

class CancelOrderItemRequest extends ApiRequest
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
            'order_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'order_id' => 'Order id',
        ];
    }
}