<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('front.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deposit', [App\Http\Controllers\FrontController::class, 'deposit'])->name('deposit');
    Route::get('/redeem', [App\Http\Controllers\RedeemController::class, 'redeem'])->name('redeem');
    Route::post('/redeem/store', [App\Http\Controllers\RedeemController::class, 'store'])->name('redeem.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
