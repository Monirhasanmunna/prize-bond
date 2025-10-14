<?php

use App\Http\Controllers\Admin\AboutCompany\HeroSectionController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.about-company.', 'prefix' => 'admin/about-company' ,'middleware' => ['auth']], function () {
    Route::group(['as'=> 'hero.', 'prefix' => 'hero' ,'middleware' => ['auth']], function () {
        Route::get('/', [HeroSectionController::class, 'getData'])->name('page');
        Route::post('/update', [HeroSectionController::class, 'update'])->name('update');
    });
});
