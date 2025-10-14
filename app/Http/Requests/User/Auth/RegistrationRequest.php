<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string',
            'email'                 => 'required|string|email|unique:users,email',
            'phone'                 => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|unique:users',
            'nid'                   => 'nullable|unique:users',
            'referral_code'         => 'nullable|string|unique:users,referral_code',
            'password'              => 'required|min:8',
            'confirm_password'      => 'required|same:password',
        ];
    }
}
