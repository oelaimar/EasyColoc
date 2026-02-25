<?php

namespace App\Http\Controllers;

use App\Models\Membership;
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
    public function create(Collection $colocation)
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:225',
        ]);

    }
}
