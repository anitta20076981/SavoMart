<?php

namespace App\Http\Requests\Api\Cart;

use App\Http\Requests\Api\ApiRequest;

class CancelCartItemRequest extends ApiRequest
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
            'product_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'product_id' => 'Product id',
        ];
    }
}