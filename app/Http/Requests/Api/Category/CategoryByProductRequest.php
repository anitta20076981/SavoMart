<?php

namespace App\Http\Requests\Api\Category;

use App\Http\Requests\Api\ApiRequest;

class CategoryByProductRequest extends ApiRequest
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

    public function rules()
    {
        return [
            'category_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'categor.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'categor' => 'Category id',
        ];
    }
}