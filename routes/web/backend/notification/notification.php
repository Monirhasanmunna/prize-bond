<?php

use App\Http\Controllers\Admin\DrawController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.notification.', 'prefix' => 'admin/notification' ,'middleware' => ['auth']], function () {
    Route::get('/list', [NotificationController::class, 'getList'])->name('list');
    Route::get('/create', [NotificationController::class, 'create'])->name('create');
    Route::post('/store', [NotificationController::class, 'store'])->name('store');
    Route::post('/update', [NotificationController::class, 'update'])->name('update');
    Route::post('/change-status', [NotificationController::class, 'changeStatus'])->name('change_status');
    Route::delete('/delete/{id}', [NotificationController::class, 'destroy'])->name('delete');
});
