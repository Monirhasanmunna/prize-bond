<?php

use App\Http\Controllers\Admin\DestinationController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.tour.', 'prefix' => 'tour' ,'middleware' => ['auth']], function () {
    Route::group(['as'=> 'destination.', 'prefix' => 'destination' ,'middleware' => ['auth']], function () {
        Route::get('/list', [DestinationController::class, 'getList'])->name('list');
        Route::post('/store', [DestinationController::class, 'store'])->name('store');
        Route::post('/update', [DestinationController::class, 'update'])->name('update');
        Route::post('/change-status', [DestinationController::class, 'changeStatus'])->name('change_status');
        Route::delete('/delete/{id}', [DestinationController::class, 'destroy'])->name('delete');
        Route::post('/restore/{id}', [DestinationController::class, 'restore'])->name('restore');
    });
});
