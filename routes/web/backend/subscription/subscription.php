<?php

use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.subscription.', 'prefix' => 'admin/subscription' ,'middleware' => ['auth']], function () {
    Route::get('/list', [SubscriptionController::class, 'getList'])->name('list');
    Route::post('/store', [SubscriptionController::class, 'store'])->name('store');
    Route::post('/update', [SubscriptionController::class, 'update'])->name('update');
    Route::post('/change-status', [SubscriptionController::class, 'changeStatus'])->name('change_status');
    Route::delete('/delete/{id}', [SubscriptionController::class, 'destroy'])->name('delete');
});
