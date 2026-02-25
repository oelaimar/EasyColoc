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

        //whet people should pay you
        $debts = Payment::where('creditor_id', $user->id)
            ->where('colocation_id', $colocationId)
            ->where('status', 'pending')
            ->with('creditor')
            ->get();
        return view('Payments.index', compact('credits', 'debts'));
    }
    public function pay(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
        return back()->with('success', 'Marked as paid.');
    }
}
