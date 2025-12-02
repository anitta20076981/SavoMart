<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('application_delete');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
