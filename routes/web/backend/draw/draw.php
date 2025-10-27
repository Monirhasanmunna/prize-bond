<?php

use App\Http\Controllers\Admin\DrawController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.draw.', 'prefix' => 'admin/draw' ,'middleware' => ['auth']], function () {
    Route::get('/list', [DrawController::class, 'getList'])->name('list');
    Route::get('/create', [DrawController::class, 'create'])->name('create');
    Route::post('/store', [DrawController::class, 'store'])->name('store');
    Route::post('/update', [DrawController::class, 'update'])->name('update');
    Route::post('/change-status', [DrawController::class, 'changeStatus'])->name('change_status');
    Route::delete('/delete/{id}', [DrawController::class, 'destroy'])->name('delete');
});
