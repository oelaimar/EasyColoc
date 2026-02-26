<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    if(auth()->check()){
        return redirect()->route('colocations.show');
    }
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    //Admin toggle the ban and unban
    Route::middleware(['auth', 'can:admin-only'])->prefix('admin')->group(function(){
       Route::post('/user/{user}/toggle-ban',[AdminUserController::class, 'toggleBan'])->name('admin.user.toggleBan');
    });

    // Users without a colocation
    Route::middleware(['single.colocation'])->group(function (){
        Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
        Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');

        Route::post('/memberships/join', [MembershipController::class, 'store'])->name('memberships.join');
    });

    // Users that have a colocation

    Route::get('/dashboard', [ColocationController::class, 'show'])->name('colocations.show');

    Route::delete('/memberships/leave', [MembershipController::class, 'destroy'])->name('memberships.leave');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Email Invitations (Owner Only)
    Route::post('/colocations/invite', [ColocationController::class, 'sendInvite'])->name('colocations.sendInvite');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
