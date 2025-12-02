<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('banner_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50|min:2',
            'banner_section_id' => 'required',
            'images' => 'required|array',
            'images.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name.max' => ':attribute must be maximum of 50 character',
            'name.min' => ':attribute must be minimum of 2 character',
            'banner_section_id.required' => ':attribute is required',
            'images.required' => ':attribute is required',
            'images.*' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'images.*' => 'Image',
            'banner_section_id' => 'Banner Section',
        ];
    }
}
