<?php

namespace App\Http\Requests;

use App\Models\Membership;
use Illuminate\Foundation\Http\FormRequest;

class SendInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        if(!$user->current_colocation_id){
            return false;
        }
        //only the owner of a colocation can send invitation
        return $this->user->memberships()
            ->where('colocation_id', $user->current_colocation_id)
            ->where('role', 'owner')
            ->exists();
    }
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:225']
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
