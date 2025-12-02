<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiRequest;

class ForgotPasswordRequest extends ApiRequest
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
            'new_password' => [
                'nullable',
                'sometimes',
                'min:6',
                'same:confirm_password',

            ],
            'confirm_password' => 'nullable|min:6',
            'phone_number' => 'required|exists:customers,phone',
        ];

    }

    public function messages()
    {
        return [
            'new_password.required' => ':attribute is required',
            'confirm_password.required' => ':attribute is required',
            'phone_number.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm Password',
            'phone_number' => 'Phone number',
        ];
    }
}
