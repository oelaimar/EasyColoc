<?php

use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware(['single.colocation'])->group(function (){
        Route::get('/colocation/create', [ColocationController::class, 'create'])->name('colocations.create');
        Route::post('/colocation/store', [ColocationController::class, 'store'])->name('colocations.store');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/colocation/dashboard', [ColocationController::class, 'show'])->name('colocations.show');

require __DIR__.'/auth.php';
