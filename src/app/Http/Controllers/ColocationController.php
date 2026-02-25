<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckSingleColocation;
use App\Models\Colocation;
use App\Models\Membership;
use App\Models\Payment;
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
            'join_at' => now()
        ]);
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('colocations.show')
            ->with('success', 'welcome to ' . $colocation->name . '!');
    }
    public function leave()
    {
        $user = auth()->user();
        $colocationId = $user->current_colocation_id;

        Membership::where('user_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('left_at')
            ->whereNull(['left_at' => now()]);

        $user->update(['current_colocation_id' => null]);

        return redirect()->route('colocations.create')
            ->with('success', 'You are left the colocation.');
    }
    public function show()
    {
        $user = auth()->user();
        $colocationId = $user->current_colocation_id;
        if (!$colocationId) {
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

        $totalSpent = $colocation->expenses()->sum('amount');

        $totalToReceive = Payment::where('creditor_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('status', 'pending')
            ->sum('amount');

        $totalToPay = Payment::where('debtor_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('status', 'pending')
            ->sum('amount');

        return view('colocation.dashboard', compact('colocation','totalSpent','totalToReceive', 'totalToPay'));

    }
}
