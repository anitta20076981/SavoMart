<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('banner_update');
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required|max:50|min:2',
            'file' => 'array',
            'file.*' => 'required|mimes:jpg,jpeg,png',
            'banner_section_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name.max' => ':attribute must be maximum of 50 character',
            'name.min' => ':attribute must be minimum of 2 character',
            'banner_section_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'file.*' => 'Image',
            'banner_section_id' => 'Banner Section',
        ];
    }
}
