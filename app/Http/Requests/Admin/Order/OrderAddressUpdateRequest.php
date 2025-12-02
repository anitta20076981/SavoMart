<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class OrderAddressUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'order_id' => 'required',
            'first_name' => 'required|string|max:50|min:1',
            'last_name' => 'required|string|max:100|min:1',
            'street_address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city' => 'required',
            'postel_code' => 'required|min:6|max:6',
            'contact' => 'required|numeric',
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
            'street_address.required' => ':attribute is required',
            'country_id.required' => ':attribute is required',
            'state_id.required' => ':attribute is required',
            'city.required' => ':attribute is required',
            'postel_code.required' => ':attribute is required',
            'postel_code.max' => ':attribute must be maximum of 6 character',
            'postel_code.min' => ':attribute must be minimum of 6 character',
            'contact.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'street_address' => 'Street Address',
            'country_id' => 'Country',
            'state_id' => 'State',
            'city' => 'City',
            'postel_code' => 'Pin Code',
            'contact' => 'Contact',
        ];
    }
}
