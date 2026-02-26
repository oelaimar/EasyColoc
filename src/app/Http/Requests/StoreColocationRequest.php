<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:225'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Please give your colocation a name.',
            'name.min' => 'The name must be at least 3 characters.',
        ];
    }
}
