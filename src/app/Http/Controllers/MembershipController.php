<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinColocationRequest;
use App\Models\Colocation;
use App\Models\Membership;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MembershipController extends Controller
{
    protected PaymentService $paymentService;
    public function __construct(PaymentService $service)
    {
        $this->paymentService = $service;
    }
    public function store(JoinColocationRequest $request)
    {
        $colocation = Colocation::where('invite_token', $request->token)
            ->where('status', 'active')
            ->first();

        if (!$colocation) {
            return back()->withErrors(['token' => 'This invitation is invalid.']);
        }
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'member',
            'join_at' => now(),
        ]);

        auth()->user()->update(['current_colocation_id' => $colocation->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Welcome to ' . $colocation->name . '!');
    }
    public function destroy(Membership $membership)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        if (!$colocation) {
            abort(403, 'You are not part of a colocation.');
        }

        $owner = $colocation->owner();

        // Guard: if we can't resolve the owner, deny access
        if (!$owner) {
            abort(403, 'Cannot determine colocation owner.');
        }

        //this is for the owner can remove member or member can leave (403 forbidding)
        if ($user->id !== $owner->id && $user->id !== $membership->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Owner cannot leave — they must delete the colocation instead
        if ($user->id === $owner->id && $membership->user_id === $owner->id) {
            return back()->withErrors(['leave' => 'As the owner, you cannot leave. You must delete the colocation instead.']);
        }
        $hasDebt = Payment::where('debtor_id', $membership->user_id)
            ->where('colocation_id', $colocation->id)
            ->where('status', 'pending')
            ->exists();
        if ($hasDebt) {
            $this->paymentService->removeMemberWithDebt(
                $membership->user_id,
                $owner->id,
            );
        }
        $isLeavingMyself = $user->id === $membership->user_id;

        $membership->update(['left_at' => now()]);
        $membership->user->update(['current_colocation_id' => null]);

        if ($isLeavingMyself) {
            return redirect()->route('colocations.create')
                ->with('success', 'You have left the colocation.');
        }

        return redirect()->route('colocations.invite')
            ->with('success', 'Member removed and outstanding debts transferred to owner.');
    }
}
