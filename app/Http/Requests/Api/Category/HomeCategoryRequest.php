<?php

namespace App\Http\Requests\Api\Category;

use App\Http\Requests\Api\ApiRequest;

class HomeCategoryRequest        extends ApiRequest
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
            'product_limit' => 'required',
            'category_limit' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_limit.required' => ':attribute is required',
            'category_limit.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'product_limit' => 'product',
            'category_limit' => 'category',
        ];
    }
}