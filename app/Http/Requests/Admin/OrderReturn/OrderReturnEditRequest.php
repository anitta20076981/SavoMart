<?php

namespace App\Http\Requests\Admin\OrderReturn;

use Illuminate\Foundation\Http\FormRequest;

class OrderReturnEditRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('order_returns_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
