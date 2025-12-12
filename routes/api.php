<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/index', [AuthController::class, 'index'])->name('home');
Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('orders', OrderController::class);
    Route::get('orders/{order}/payments', [PaymentController::class, 'indexForOrder']);
    Route::post('orders/{order}/payments', [PaymentController::class, 'process']);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('payments/{payment}', [PaymentController::class, 'show']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
