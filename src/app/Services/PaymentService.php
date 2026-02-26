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
        $activeMembers = $colocation->member()->withPivot('left_at', null)->get();
        $count = $activeMembers->count();
        if($count <= 1) return;

        $splitAmount = round($expense->amount / $count, 2);

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
        db::transaction(function () use ($memberId, $ownerId){
            Payment::where('debtor_id',$memberId)
                ->where('status', 'pending')
                ->update(['debtor_id' => $ownerId]);
        });
        $member = User::findOrFail($memberId);
        $member->decrement('reputation_score', 1);
    }
}
