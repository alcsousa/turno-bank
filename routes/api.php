<?php

use App\Http\Controllers\Admin\CheckControlController;
use App\Http\Controllers\CheckController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checks', [CheckController::class, 'index']);
    Route::post('/checks', [CheckController::class, 'store']);
});

Route::middleware(['auth:sanctum', EnsureUserIsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/checks', [CheckControlController::class, 'indexByStatus']);
    Route::get('/checks/{check}', [CheckControlController::class, 'show']);
});
