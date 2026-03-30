<?php

use App\Http\Controllers\MedicationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Medication routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/medications/search', [MedicationController::class, 'search']);
});

// Order routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/alert', [OrderController::class, 'sendAlert']);
});

// Customer routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
});
