<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PagesCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('pages_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'title' => 'nullable',
            'slug' => 'required|unique:pages,slug',
            'file' => 'nullable|mimes:application/msword,application/pdf,application/vnd.ms-excel,application/vnd.ms-powerpoint,application/zip,audio/mpeg,audio/mp3,image/gif,image/jpeg,image/webp,text/plain,video/x-m4v,jpeg,jpg,png,pdf',
            'thumbnail' => 'nullable|mimes:jpeg,jpg,png',
            'status' => 'required',
            'content' => 'nullable',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'title.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
            'file.required' => ':attribute is required',
            'thumbnail.required' => ':attribute is required',
            'status.required' => ':attribute is required',
            'is_deletable.required' => ':attribute is required',
            // 'content.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'title' => 'Title',
            'slug' => 'Slug',
            'status' => 'Status',
            'is_deletable' => 'Deletable',
            'content' => 'Content',
            'thumbnail' => 'Thumbnail',
            'file' => 'File',
        ];
    }
}
