<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiRequest;

class RegisterRequest extends ApiRequest
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
            'first_name' => 'required',
            'phone' => 'required|unique:customers|min:10|max:10',
            'email' => 'required|email|unique:customers',
            'register_password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => ':attribute is required',
            'email.required' => ':attribute is required',
            'phone.required' => ':attribute is required',
            'register_password.required' => ':attribute is required',
            'confirm_password.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'register_password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'customer_register_accept_terms' => 'Terms and Conditions',
        ];
    }
}
