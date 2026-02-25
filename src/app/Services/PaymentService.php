<?php

namespace App\Services;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\User;

class PaymentService
{
    public function splitExpense(Expense $expense)
    {
        $colocation = $expense->colocation;
        $activeMembers = $colocation->members;
        $count = $activeMembers->count();
        $splitAmount = $expense->amount / $count;

        foreach ($activeMembers as $member){
            if($member->id !== $expense->user_id){
                Payment::create([
                    'colocation_id' => $colocation->id,
                    'debtor_id' => $member->id,
                    'creditor_id' => $expense->user_id,
                    'amount' => $splitAmount,
                    'status' => 'pending',
                ]);
            }
        }
    }
    //transfer the dept to the owner
    public function removeMemberWithDept(int $memberId,int $ownerId)
    {
        Payment::where('debtor_id', $memberId)
            ->where('status', 'pending')
            ->update(['debtor_id' => $ownerId]);
        $member = User::find($memberId);
        $member->reputation_score -= 1;
        $member->save();
    }
}
