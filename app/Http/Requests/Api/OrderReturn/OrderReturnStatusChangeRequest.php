<?php

namespace App\Http\Requests\Api\OrderReturn;

use App\Http\Requests\Api\ApiRequest;

class OrderReturnStatusChangeRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_return_id' => 'required|exists:order_returns,id',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_return_id.required' => ':attribute is required',
            'status.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'order_return_id' => 'Order Return',
            'status' => 'Status',
        ];
    }
}
