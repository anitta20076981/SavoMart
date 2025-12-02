<?php

namespace App\Http\Requests\Api\OrderReturn;

use App\Http\Requests\Api\ApiRequest;

class OrderReturnAddRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_item_id' => 'required|exists:order_items,id',
            'reason' => 'required',
            'location' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_item_id.required' => ':attribute is required',
            'reason' => ':attribute is required',
            'location' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'order_item_id' => 'Order Item',
            'reason' => 'Reason',
            'location' => 'Location',
        ];
    }
}
