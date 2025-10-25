<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'profile', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/edit', [ProfileController::class, 'edit']);
    Route::post('/update', [ProfileController::class, 'update']);
});
