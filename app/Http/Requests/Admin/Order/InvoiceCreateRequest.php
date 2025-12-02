<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('invoice_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'order_id' => 'Order Id',
        ];
    }
}
