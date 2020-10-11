<?php

use App\Http\Controllers\CopernicaAuthController;
use App\Http\Controllers\LightspeedDiscountController;
use App\Http\Controllers\LightspeedOrderController;
use App\Http\Controllers\LightspeedSubscriberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LightspeedAuthController;

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::get('/', [AuthController::class, 'dashboard']);
    Route::get('copernica', [AuthController::class, 'copernica']);
    Route::get('lightspeed', [AuthController::class, 'copernica']);
    Route::get('lightspeed/subscribers', [LightspeedSubscriberController::class, 'show']);
    Route::get('lightspeed-auth/settings', [LightspeedAuthController::class, 'edit']);
    Route::post('lightspeed-auth/settings', [LightspeedAuthController::class, 'update']);
    Route::get('lightspeed/discounts', [LightspeedDiscountController::class, 'show']);
    Route::get('lightspeed/orders', [LightspeedOrderController::class, 'show']);
    Route::get('copernica-auth/settings', [CopernicaAuthController::class, 'edit']);
    Route::post('copernica-auth/settings', [CopernicaAuthController::class, 'update']);
});

Route::get('login', [AuthController::class, 'index'])->name('login');;
Route::post('login', [AuthController::class, 'postLogin']); 
Route::get('registration', [AuthController::class, 'registration']);
Route::post('registration', [AuthController::class, 'postRegistration']);
Route::get('logout', [AuthController::class, 'logout']);
