<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;

class PasswordChangeRequest extends ApiRequest
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
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required_with:new_password|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => ':attribute is required',
            'new_password.required' => ':attribute is required',
            'confirm_password.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'new_password' => 'New Password',
            'confirm_password' => 'New Password',
            'current_password' => 'New Password',
        ];
    }
}
