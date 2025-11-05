<?php

use App\Http\Controllers\User\BondSeriesController;
use App\Http\Controllers\User\NotificationController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'notification', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/list', [NotificationController::class, 'getList']);
    Route::get('/{id}', [NotificationController::class, 'getData']);
});
