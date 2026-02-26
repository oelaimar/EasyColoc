<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->current_colocation_id !== null;
    }
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category_id' => ['required', 'exists:categories,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
