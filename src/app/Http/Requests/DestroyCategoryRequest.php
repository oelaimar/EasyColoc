<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');
        $user = $this->user();

        return \App\Models\Membership::where('user_id', $user->id)
            ->where('colocation_id', $category->colocation_id)
            ->where('role', 'owner')
            ->exists();
    }
    public function rules(): array
    {
        return [];
    }
}
