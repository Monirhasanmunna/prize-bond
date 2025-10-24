<?php

namespace App\Http\Requests\User\PrizeBond;

use Illuminate\Foundation\Http\FormRequest;

class PrizeBondStoreRequest extends FormRequest
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
            'bond_series_id' => 'required|exists:bond_series,id',
            'price' => 'required|numeric',
            'code'  => 'required|string|unique:prize_bonds,code',
        ];
    }
}
