<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinColocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:32']
        ];
    }
    public function messages(): array
    {
        return [
            'token.required' => 'You must provide an invitation token.',
            'token.size' => 'That token is invalid (it must be exactly 32 characters.)'
        ];
    }
}
