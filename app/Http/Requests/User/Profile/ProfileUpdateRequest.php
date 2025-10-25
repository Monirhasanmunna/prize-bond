<?php

namespace App\Http\Requests\User\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'nid' => 'nullable|string|unique:users,nid',
            'name' => 'nullable|string',
            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:11',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:5112',
        ];
    }
}
