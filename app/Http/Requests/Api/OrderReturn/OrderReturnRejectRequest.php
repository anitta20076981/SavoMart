<?php

namespace App\Http\Requests\Api\OrderReturn;

use App\Http\Requests\Api\ApiRequest;

class OrderReturnRejectRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_return_id' => 'required|exists:order_returns,id',
            'reason' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_return_id.required' => ':attribute is required',
            'reason.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'reason' => 'Order Return',
        ];
    }
}
