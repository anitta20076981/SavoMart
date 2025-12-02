<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class CustomerCreateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_create');
    }

    public function rules(Request $request)
    {
        return [
            'first_name' => 'required|string|max:50|min:1',
            'last_name' => 'required|string|max:100|min:1',
            'email' => 'nullable|email|unique:customers',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required_with:password|same:password',
            'account_no' => 'nullable|min:10|max:16',
            'pin_code' => 'nullable|min:6|max:6',
            'aadhar_number' => 'nullable|min:12|max:12',
            'gst_number' => 'required_if:has_gst,1',
            'gst_certificate' => 'required_if:has_gst,1',
            'gst_date_of_in_corparation' => 'required_if:has_gst,1',
            'phone' => 'required|unique:customers|min:5|max:15',

        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => ':attribute is required',
            'first_name.max' => ':attribute must be maximum of 50 character',
            'first_name.min' => ':attribute must be minimum of 1 character',
            'last_name.required' => ':attribute is required',
            'last_name.max' => ':attribute must be maximum of 50 character',
            'last_name.min' => ':attribute must be minimum of 1 character',
            'email.required' => ':attribute is required',
            'phone.required' => ':attribute is required',
            'phone.unique' => ':attribute must be unique',
            'phone.max' => ':attribute must be maximum of 15 character',
            'phone.min' => ':attribute must be minimum of 5 character',
            'password.required' => ':attribute is required',
            'password.min' => ':attribute must be mininmum of 6 characters',
            'confirm_password' => ':attribute must be same as Password',
            'account_no.max' => ':attribute must be maximum of 16 character',
            'account_no.min' => ':attribute must be minimum of 10 character',
            'pin_code.max' => ':attribute must be maximum of 6 character',
            'pin_code.min' => ':attribute must be minimum of 6 character',
            'aadhar_number.max' => ':attribute must be maximum of 12 character',
            'aadhar_number.min' => ':attribute must be minimum of 12 character',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'account_no' => 'Account Number',
            'pin_code' => 'Pin Code',
            'aadhar_number' => 'Aadhar Number',
        ];
    }
}
