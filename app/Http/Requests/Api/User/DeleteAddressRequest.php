<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;

class DeleteAddressRequest extends ApiRequest
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
            'address_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'address_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'address_id' => 'Address id',
        ];
    }
}
