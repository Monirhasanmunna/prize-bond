<?php

use App\Http\Controllers\User\StatsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'statistics', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [StatsController::class, 'getData']);
});
