<?php

namespace App\Http\Requests\Api\OrderReturn;

use App\Http\Requests\Api\ApiRequest;

class OrderReturnListRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}
