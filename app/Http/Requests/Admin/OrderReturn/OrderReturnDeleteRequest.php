<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_return_delete');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
