<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $colocation = $user->currentColocation;

        $query = $colocation->expenses()->with(['category','payer']);

        if($request->has('month') && $request->month != 'all'){
            $date = Carbon::parse($request->month);
            $query->whereMonth('date', $date->month)
                ->whereYear('date', $date->year);
        }

        $expenses = $query->get();
        $categories = Category::all();

        return view('expenses.index', compact('expenses', 'categories'));
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
}
