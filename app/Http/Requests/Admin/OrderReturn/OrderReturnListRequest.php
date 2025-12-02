<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnListRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_returns_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
