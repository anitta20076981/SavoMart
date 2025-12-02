<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;

class CustomerEditRequest extends ApiRequest
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
            'first_name' => 'nullable|string|max:50|min:1',
            'email' => 'nullable|max:50|email|unique:customers,email,' . auth()->user()->id . ',id,deleted_at,NULL',
            'phone' => 'nullable|min:5|max:15|unique:customers,phone,' . auth()->user()->id . ',id,deleted_at,NULL|min:5|max:15',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => ':attribute is required',
            'email.required' => ':attribute is required',
            'phone.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
}
