<?php

namespace App\Http\Requests\Admin\DataTransfer;

use Illuminate\Foundation\Http\FormRequest;

class HandleExportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'entity_type' => 'required',
            'export_type' => 'required', 
        ];
    }

    public function messages()
    {
        return [ 
            'entity_type.required' => ':attribute is required',
            'export_type.required' => ':attribute is required', 

        ];
    }

    public function attributes()
    {
        return [ 
            'entity_type' => 'Entity Type',
            'export_type' => 'Export File Format', 
        ];
    }
}