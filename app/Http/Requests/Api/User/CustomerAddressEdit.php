<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;

class CustomerAddressEdit extends ApiRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_address_id' => 'required|exists:customer_details,id',
            'address' => 'nullable',
            'street' => 'nullable',
            'phone' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'customer_address_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'customer_address_id' => 'Address Id',
            'address' => 'Address',
            'street' => 'Street',
            'phone' => 'Phone',
        ];
    }
}
