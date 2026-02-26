<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Category;
use App\Models\Payment;
use App\Services\PaymentService;
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
    public function store(StoreExpenseRequest $request, PaymentService $paymentService)
    {

        $user = auth()->user();
        $colocation = $user->currentColocation;

        $expense = $colocation->expenses()->create($request->validated() + [
                'user_id' => auth()->id()
            ]);
        $paymentService->splitExpense($expense);

        return back()->with('success', 'Expense split successfully!');
    }
}
