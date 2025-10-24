<?php

use App\Http\Controllers\User\PrizeBondController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'prize-bond', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/list', [PrizeBondController::class, 'getList']);
    Route::post('/store', [PrizeBondController::class, 'store']);
    Route::post('/bulk-store', [PrizeBondController::class, 'bulkStore']);
    Route::post('/update', [PrizeBondController::class, 'update']);
    Route::delete('/destroy/{id}', [PrizeBondController::class, 'destroy']);
});
