<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:customers|min:10|max:10',
            'email' => 'required|email|unique:customers',
            'register_password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6',
            'customer_register_accept_terms' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => ':attribute is required',
            'last_name.required' => ':attribute is required',
            'email.required' => ':attribute is required',
            'phone.required' => ':attribute is required',
            'register_password.required' => ':attribute is required',
            'confirm_password.required' => ':attribute is required',
            'customer_register_accept_terms.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'register_password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'customer_register_accept_terms' => 'Terms and Conditions',
        ];
    }
}
