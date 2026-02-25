<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckSingleColocation;
use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColocationController extends Controller
{
    public function create(Request $request)
    {
        return view('colocation.create');
    }
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|string|max:225'
        ]);

        //create Colocation
        $colocation = Colocation::create([
            'name' => $request->name,
            'invite_token' => Str::random(32),
            'status' => 'active',
        ]);
        //create Membership
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'join_at' => now(),
        ]);

        //update user currentColocation
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Your Colocation' . $colocation->name . 'has been created!!');
    }
    public function join(Request $request)
    {
        $request->validate(['token' => 'required|string|size:32']);

        $colocation = Colocation::where('invite_token', $request->token)
                ->where('status', 'active')
                ->first();

        if (!$colocation) {
            return back()->withErrors(['token' => 'This invitation token is invalid or the group no longer exists.']);
        }
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'member',
            'joined_at' => now()
        ]);
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('colocations.show')
            ->with('success', 'welcome to ' . $colocation->name . '!');
    }
    public function show()
    {
        $user = auth()->user();

        if (!$user->current_colocation_id) {
            return redirect()->route('colocations.create');
        }

        //the colocation with all member that enroll on it
        $colocation = Colocation::with([
            'members' => function($query){
                $query->wherePivot('left_at', null);
            },
            'expenses' => function($query) {
                $query->latest()->limit(5);
            }
            ])->findOrFail($user->current_colocation_id);

        $totalSpent = $colocation->expenses->sum('amount');
        return view('colocation.dashboard', compact('colocation','totalSpent'));
    }
}
