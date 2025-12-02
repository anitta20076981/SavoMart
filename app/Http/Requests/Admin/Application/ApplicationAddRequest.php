<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationAddRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('application_create');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
