<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function toggleBan(User $user)
    {
        if(auth()->user() === $user->id){
            return back()->with('error', 'You cannot ban yourself!');
        }
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banned' : 'activated';

        return back()->with('success'. "user {$user->name} have been {$status}");
    }
}
