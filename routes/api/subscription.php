<?php

use App\Http\Controllers\User\SubscriptionController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'subscription', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/list', [SubscriptionController::class, 'getList']);
});
