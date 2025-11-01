<?php

use App\Http\Controllers\PaymentGateway\PaystationGatewayController;
use Illuminate\Support\Facades\Route;

Route::get('/success', [PaystationGatewayController::class, 'paymentSuccess'])->name('paystation.success');
