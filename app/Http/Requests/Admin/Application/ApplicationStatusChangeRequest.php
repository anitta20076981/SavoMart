<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationStatusChangeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('application_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
