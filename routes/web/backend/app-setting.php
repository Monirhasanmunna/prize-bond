<?php

use App\Http\Controllers\Admin\AppSettingController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.app-setting.', 'prefix' => 'app-setting' ,'middleware' => ['auth']], function () {
    Route::get('/', [AppSettingController::class, 'showList'])->name('page');
    Route::post('/update', [AppSettingController::class, 'update'])->name('update');
});
