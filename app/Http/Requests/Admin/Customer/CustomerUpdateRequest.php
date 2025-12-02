<?php

namespace App\Http\Requests\Admin\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('customer_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'id' => 'required',
            'first_name' => 'required|string|max:50|min:1',
            'last_name' => 'required|string|max:100|min:1',
            'email' => 'required|max:50|email|unique:customers,email,' . $this->id,
            'phone' => 'nullable|string',
            'phone' => 'required|min:5|max:15|unique:customers,phone,' . $this->id,

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

        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',

        ];
    }
}