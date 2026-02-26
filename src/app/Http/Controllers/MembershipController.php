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

        return redirect()->route('colocations.show')
            ->with('success', 'Welcome to ' . $colocation->name . '!');
    }
    public function destroy(Membership $membership)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        //this is for the owner can remove member or member can leave(403 forbidding)
        if ($user->id !== $colocation->owner()->id && $user->id !== $membership->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $hasDebt = Payment::where('debtor_id', $membership->user_id)
            ->where('colocation_id', $colocation->id)
            ->where('status', 'pending')
            ->exists();
        if ($hasDebt) {
            $this->paymentService->removeMemberWithDebt(
                $membership->user_id,
                $colocation->owner()->id,
            );
        }
        $membership->update(['left_at' => now()]);
        $membership->user->update(['current_colocation_id' => null]);

        return redirect()->route('colocations.create')
            ->with('success', 'Member removed and outstanding debts transferred to owner.');
    }
}
