<?php

namespace App\Http\Requests\Admin\Contents;

use Illuminate\Foundation\Http\FormRequest;

class ContentsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('contents_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->all());
        return [
            'id' => 'required',
            'name' => 'required',
            'title' => 'required',
            'slug' => 'required|unique:contents,id,' . $this->id,
            // 'file' => 'nullable|mimes:jpeg,jpg,png,mp4,x-msvideo,x-ms-wmv,doc,pdf,docx,application/octet-stream',
            'file' => 'required_if:file_label,1|mimes:application/msword,application/pdf,application/vnd.ms-excel,application/vnd.ms-powerpoint,application/zip,audio/mpeg,audio/mp3,image/gif,image/jpeg,image/webp,text/plain,video/x-m4v,jpeg,jpg,png,pdf',
            'thumbnail' => 'nullable|mimes:jpeg,jpg,png',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'title.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
            'slug.unique' => ':attribute must be unique',
            'file.required_if' => ':attribute is required',
            'thumbnail.required' => ':attribute is required',
            'status.required' => ':attribute is required',
            'is_deletable.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'id' => 'Content',
            'name' => 'Name',
            'title' => 'Title',
            'slug' => 'Slug',
            'status' => 'Status',
            'is_deletable' => 'Deletable',
        ];
    }
}
