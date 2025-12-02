<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user_create');
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
            'phone' => 'required',
            'password' => 'required|min:6',
            'password_confirm' => 'required_with:password|same:password',
            // 'role_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'phone.required' => ':attribute is required',
            'email.unique' => ':attribute already exist',
            'email.email' => 'Please enter valid :attribute address',
            'password.required' => ':attribute is required',
            'password.min' => ':attribute must be minimum of 6 character',
            'password_confirm.required' => ':aattribute is required',
            'password_confirm.same' => ':attribute must be same as Password',
            'role_id.required' => ':attribute is required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'phone' => 'Phone',
            'password' => 'Password',
            'password_confirm' => 'Confirm Password',
            'role_id' => 'Role',
        ];
    }
}
