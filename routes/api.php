<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/loan', [LoanController::class, 'store'])->name('loan.store');
    Route::get('/loan/{loan}', [LoanController::class, 'show'])->name('loan.show');
    Route::patch('/loan/{loan}/approve', [LoanController::class, 'approve'])->name('loan.approve');
    Route::patch('/loan/{loan}/repay', [LoanController::class, 'repay'])->name('loan.repay');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');