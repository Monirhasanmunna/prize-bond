<?php

namespace App\Http\Requests\Backend\BioSection;

use Illuminate\Foundation\Http\FormRequest;

class BioSectionUpdateRequest extends FormRequest
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
        $rules = [
            'description' => 'required|string',
        ];

        if($this->input('id')) {
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120';
        }
        else{
            $rules['image'] = 'required|mimes:jpeg,png,jpg,gif,svg|max:5120';
        }

        return $rules;
    }
}
