<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        //
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories.id',
            'date' => 'required|date',
        ]);
        $user = auth()->user();
        $colocation = $user->currentColocation;

        $expense = $colocation->expense()->create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);
        // Logic to create payments (Who owes Who)
        $members = $colocation->member;
        $count = $members->count();
        $share = $request->amount / $count;

        foreach ($members as $member){
            if($member->id !== $user->id){
                Payment::create([
                    'colocation_id' => $colocation->id,
                    'debtor_id' => $member->id,
                    'creditor_id' => $user->id,
                    'amount' => $share,
                    'status' => 'pending',
                ]);
            }
        }
        return back()->with('success', 'Expense added and split!');
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
