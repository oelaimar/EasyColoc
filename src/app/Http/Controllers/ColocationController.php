<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendInviteRequest;
use App\Http\Requests\StoreColocationRequest;
use App\Mail\ColocationInvite;
use App\Models\Colocation;
use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ColocationController extends Controller
{
    public function create()
    {
        return view('colocation.create');
    }

    public function store(StoreColocationRequest $request)
    {
        // Create Colocation
        $colocation = Colocation::create([
            'name' => $request->name,
            'invite_token' => Str::random(32),
            'status' => 'active',
        ]);

        // Create Membership
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'join_at' => now(),
        ]);

        // Update user currentColocation
        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        // Bug 6 fixed: no route parameter passed to a parameterless route
        return redirect()->route('dashboard')
            ->with('success', 'Your Colocation ' . $colocation->name . ' has been created!');
    }

    public function show()
    {
        $user = auth()->user();
        $colocationId = $user->current_colocation_id;

        if (!$colocationId) {
            return redirect()->route('colocations.create');
        }

        // Bug 9 fixed: removed redundant ->wherePivotNull('left_at') — already in members() scope
        $colocation = Colocation::with([
            'members',
            'expenses' => function ($query) {
                $query->with('user')->latest()->limit(5);
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

        return view('colocation.dashboard', compact('colocation', 'totalSpent', 'totalToReceive', 'totalToPay'));
    }

    public function invitePage()
    {
        return view('colocation.invite');
    }

    public function sendInvite(SendInviteRequest $request)
    {
        $colocation = auth()->user()->currentColocation;

        Mail::to($request->email)->send(new ColocationInvite($colocation));

        // Bug 16 fixed: added missing space before email address
        return back()->with('success', 'Invitation sent to ' . $request->email);
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        if (!$colocation) {
            return redirect()->route('colocations.create');
        }

        $owner = $colocation->owner();

        // Only the owner can delete
        if (!$owner || $user->id !== $owner->id) {
            abort(403, 'Only the owner can delete this colocation.');
        }

        // Validate password for confirmation
        $request->validateWithBag('colocationDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Detach all active members (clear their current_colocation_id)
        $colocation->memberships()
            ->whereNull('left_at')
            ->with('user')
            ->get()
            ->each(function ($membership) {
                $membership->user?->update(['current_colocation_id' => null]);
            });

        $colocation->delete();

        return redirect()->route('colocations.create')
            ->with('success', 'The colocation has been deleted.');
    }
}
