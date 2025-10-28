<?php

use App\Http\Controllers\User\DrawController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'draw', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/list', [DrawController::class, 'getList']);
    Route::post('/check-winner', [DrawController::class, 'checkWinner']);
});
