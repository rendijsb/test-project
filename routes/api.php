<?php

use App\Http\Controllers\Api\Customers\CustomerController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::apiResource('orders', OrderController::class)->names('api.orders');
    Route::get('customers/{customer}/orders', [OrderController::class, 'customerOrders'])->name('api.customers.orders');
    Route::apiResource('users', UserController::class)->names('api.users');
});