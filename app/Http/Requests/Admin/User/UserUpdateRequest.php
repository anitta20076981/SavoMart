<?php

namespace App\Http\Requests\Admin\User;

// use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Request;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'phone' => 'required',
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
            'password_confirm.required' => ':attribute is required',
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
