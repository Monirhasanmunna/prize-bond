<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/customer/registration', [AuthController::class, 'registration']);
Route::post('/customer/verify-otp', [AuthController::class, 'verificationOtp']);
Route::post('/customer/login', [AuthController::class, 'login']);
Route::post('/customer/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/customer/forgot-password', [AuthController::class, 'sendOtp']);
Route::post('/customer/reset-password', [AuthController::class, 'resetPassword']);
