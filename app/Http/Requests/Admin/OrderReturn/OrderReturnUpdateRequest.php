<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class OrderReturnUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_returns_update');
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
            // 'order_no' => 'required',
            'reason' => 'required',
            'location' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'order_no.required' => ':attribute is required',
            'reason.required' => ':attribute is required',
            'location.required' => ':attribute is required',

        ];
    }

    public function attributes()
    {
        return [
            // 'order_no' => 'Order No',
            'reason' => 'Reason',
            'location' => 'Location',
        ];
    }
}
