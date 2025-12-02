<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_update');
    }

    public function rules()
    {
        return [
            'order_id' => 'required',
            'customer_id' => 'required',
            'billing_address_address_1' => 'required',
            'billing_address_city' => 'required',
            'billing_address_postcode' => 'required',
            'billing_address_state' => 'required',

            'shipping_address_address_1' => 'required_if:same_as_billing,0',
            'shipping_address_city' => 'required_if:same_as_billing,0',
            'shipping_address_postcode' => 'required_if:same_as_billing,0|min:6|max:6',
            'shipping_address_state' => 'required_if:same_as_billing,0',
            'products.*' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => ':attribute is required',
            'billing_address_address_1.required' => ':attribute is required',
            'billing_address_city.required' => ':attribute is required',
            'billing_address_postcode.required' => ':attribute is required',
            'billing_address_state.required' => ':attribute is required',
            'shipping_address_address_1.required' => ':attribute is required',
            'shipping_address_city.required' => ':attribute is required',
            'shipping_address_postcode.required' => ':attribute is required',
            'shipping_address_state.required' => ':attribute is required',
            'products.required' => 'Please add at least one product',
        ];
    }

    public function attributes()
    {
        return [
            'customer_id' => 'Customer',
            'billing_address_address_1' => 'Billing Address',
            'billing_address_city' => 'Billing City',
            'billing_address_postcode' => 'Billing Pincode',
            'billing_address_state' => 'Billing State',
            'shipping_address_address_1' => 'Shipping Address',
            'shipping_address_city' => 'Shipping City',
            'shipping_address_postcode' => 'Shipping Pincode',
            'shipping_address_state' => 'Shipping State',
            'products' => 'required|array',
        ];
    }
}
