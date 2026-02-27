<?php

namespace App\Http\Requests;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;

class SendInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        //only the owner of a colocation can send invitation
        return $user->memberships()
            ->where('colocation_id', $user->current_colocation_id)
            ->where('role', 'owner')
            ->exists();
    }
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255']
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Please enter the email address of the person you want to invite.',
            'email.email' => 'This must be a valid email address.',
        ];
    }
}
