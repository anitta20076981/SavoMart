<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiRequest;

class GenerateOtpRequest extends ApiRequest
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
            'phone_number' => 'required|exists:customers,phone',
        ];

    }

    public function messages()
    {
        return [
            'phone_number.required_if' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'phone_number' => 'Phone',
        ];
    }
}
