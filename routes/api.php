<?php

use App\Http\Controllers\Api\Customers\CustomerController;
use App\Http\Controllers\Api\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::apiResource('orders', OrderController::class)->names('api.orders');
    Route::get('customers/{customer}/orders', [OrderController::class, 'customerOrders'])->name('api.customers.orders');
});