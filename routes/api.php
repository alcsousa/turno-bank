<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CheckControlController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', EnsureUserIsCustomer::class])->group(function () {
    Route::get('/checks', [CheckController::class, 'indexByStatus']);
    Route::post('/checks', [CheckController::class, 'store']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/accounts/user', [AccountController::class, 'getUserAccount']);
});

Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/checks', [CheckControlController::class, 'indexByStatus']);
    Route::get('/checks/{check}', [CheckControlController::class, 'show']);
    Route::post('/checks/{check}/evaluate', [CheckControlController::class, 'evaluateCheck']);
});
