<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnListDataRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_return_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
