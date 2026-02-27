<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $totalUsersCount      = User::count();
        $bannedUsersCount     = User::where('is_banned', true)->count();
        $totalColocationsCount = Colocation::count();
        $totalExpensesSum     = Expense::sum('amount');
        $users                = User::paginate(10);

        return view('admin.users.index', compact(
            'totalUsersCount',
            'bannedUsersCount',
            'totalColocationsCount',
            'totalExpensesSum',
            'users'
        ));
    }

    public function toggleBan(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot ban yourself!');
        }
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banned' : 'activated';

        return back()->with('success', "user {$user->name} have been {$status}");
    }
}
