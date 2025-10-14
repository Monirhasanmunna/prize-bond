<?php

namespace App\Http\Requests\Admin\Series;

use Illuminate\Foundation\Http\FormRequest;

class SeriesStoreRequest extends FormRequest
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
            'name' => 'required|unique:bond_series,name',
            'code' => 'required|unique:bond_series,code',
        ];
    }
}
