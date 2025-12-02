<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductsCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('products_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'productName' => 'required',
            'name_ar' => 'required',
            // 'description' => 'required', 
            'sku' => 'required|unique:products,sku', 
            'categories' => 'nullable|array',
            'thambnail' => 'nullable|image|mimes:jpg,png',   
        ];
    }

    public function messages()
    {
        return [ 
            'productName.required' => ':attribute is required',
            'name_ar.required' => ':attribute is required',
            'description.required' => ':attribute is required', 
            'sku.required' => ':attribute is required'

        ];
    }

    public function attributes()
    {
        return [ 
            'productName' => 'English Product Name',
            'name_ar' => 'Arabic Product Name',
            'description' => 'Description', 
            'sku' => 'SKU',
            'quantity' => 'Quantity', 
            'categories' => 'Category',
            'thambnail' => 'Thambnail'

        ];
    }
}