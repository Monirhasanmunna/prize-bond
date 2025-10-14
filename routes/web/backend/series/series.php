<?php

use App\Http\Controllers\Admin\SeriesController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.series.', 'prefix' => 'admin/series' ,'middleware' => ['auth']], function () {
    Route::get('/list', [SeriesController::class, 'getList'])->name('list');
    Route::post('/store', [SeriesController::class, 'store'])->name('store');
    Route::post('/update', [SeriesController::class, 'update'])->name('update');
    Route::post('/change-status', [SeriesController::class, 'changeStatus'])->name('change_status');
    Route::delete('/delete/{id}', [SeriesController::class, 'destroy'])->name('delete');
});
