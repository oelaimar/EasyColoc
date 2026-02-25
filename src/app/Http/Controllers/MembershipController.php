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
    public function index()
    {
        //
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

    /**
     * Display the specified resource.
     */
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
