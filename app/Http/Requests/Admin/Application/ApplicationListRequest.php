<?php

namespace App\Http\Requests\Admin\Application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationListRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('application_read');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
