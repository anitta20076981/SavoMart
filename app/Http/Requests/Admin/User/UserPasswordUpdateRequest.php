<?php

namespace App\Http\Requests\Admin\User;

// use GuzzleHttp\Psr7\Request;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserPasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $request->has('current') || auth()->user()->can('user_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'password' => 'required|min:6',
            'password_confirm' => 'required_with:password|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => ':attribute is required',
            'password.min' => ':attribute must be minimum of 6 character',
            'password_confirm.required' => ':aattribute is required',
            'password_confirm.same' => ':attribute must be same as Password',
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'Password',
            'password_confirm' => 'Confirm Password',
        ];
    }
}
