<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $colocation = $user->currentColocation;

        //only the owner of a colocation can create Category
        return $colocation && $user->memberships()
            ->where('colocation_id', $colocation->id)
            ->where('role', 'owner')
            ->exists();
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50']
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'the name is required',
        ];
    }
}
