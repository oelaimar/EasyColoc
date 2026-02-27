<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('/');

Route::middleware('auth')->group(function () {

    // Admin routes (admin-only gate)
    Route::middleware(['auth', 'can:admin-only'])->prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/user/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('admin.user.toggleBan');
    });

    // Users without a colocation
    Route::middleware(['single.colocation'])->group(function () {
        Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
        Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');

        Route::post('/memberships/join', [MembershipController::class, 'store'])->name('memberships.join');
    });

    // Users that have a colocation
    Route::get('/dashboard', [ColocationController::class, 'show'])->name('dashboard');

    Route::delete('/memberships/{membership}/leave', [MembershipController::class, 'destroy'])->name('memberships.leave');

    Route::get('/colocations/invite', [ColocationController::class, 'invitePage'])->name('colocations.invite');

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

    // Email Invitations — POST only (send the invite email)
    Route::post('/colocations/invite', [ColocationController::class, 'sendInvite'])->name('colocations.sendInvite');

    // Delete colocation (owner only)
    Route::delete('/colocations', [ColocationController::class, 'destroy'])->name('colocations.destroy');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
