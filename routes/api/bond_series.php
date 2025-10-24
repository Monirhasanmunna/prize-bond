<?php

use App\Http\Controllers\User\BondSeriesController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'bond-series', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/list', [BondSeriesController::class, 'getList']);
});
