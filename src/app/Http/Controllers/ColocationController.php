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
        return Colocation::create([
            'name' => $request->name,
            'token' => Str::random(32),
            'status' => 'active',
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|string|max:225'
        ]);

        //if user is already in a colocation
        if(auth()->user()->current_colocation_id){
            return back()->with('error', 'you are already in a colocation.');
        }

        //create Colocation
        $colocation = $this->create($request);
        //create Membership
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        //update user currentColocation
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation created!');
    }
    public function join(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $colocation = Colocation::where('token', $request->token)->firstOrFail();
        //if user is already in a colocation
        if(auth()->user()->current_colocation_id){
            return back()->with('error', 'you are already in a colocation.');
        }
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'member',
            'joined_at' => now()
        ]);
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('colocations.show', $colocation);
    }
}
