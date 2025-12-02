<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnAddRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_return_create');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
