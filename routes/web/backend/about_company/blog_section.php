<?php

use App\Http\Controllers\Admin\AboutCompany\BlogSectionController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.about-company.', 'prefix' => 'admin/about-company' ,'middleware' => ['auth']], function () {
    Route::group(['as'=> 'blog.', 'prefix' => 'blog' ,'middleware' => ['auth']], function () {
        Route::get('/list', [BlogSectionController::class, 'getList'])->name('list');
        Route::post('/store', [BlogSectionController::class, 'store'])->name('store');
        Route::post('/update', [BlogSectionController::class, 'update'])->name('update');
        Route::post('/change-status', [BlogSectionController::class, 'changeStatus'])->name('change_status');
        Route::delete('/delete/{id}', [BlogSectionController::class, 'destroy'])->name('delete');
    });
});
