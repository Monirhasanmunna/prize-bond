<?php

use App\Http\Controllers\PaymentGateway\PaystationGatewayController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'paystation'], function () {
    Route::post('payment', [PaystationGatewayController::class, 'payment']);
});

