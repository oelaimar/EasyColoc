<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $colocationId = $user->current_colocation_id;

        //what User should pay people
        $credits = Payment::where('creditor_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('status', 'pending')
            ->with('debtor')
            ->get();

        $debts = Payment::where('debtor_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('status', 'pending')
            ->with('creditor')
            ->get();

        return view('payments.index', compact('credits', 'debts'));
    }

    public function pay(Payment $payment)
    {
        if (auth()->id() !== $payment->debtor_id) {
            abort(403, 'You are not authorized to mark this payment as paid.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        $payment->debtor->increment('reputation_score', 1);

        return back()->with('success', 'Marked as paid.');
    }
}
