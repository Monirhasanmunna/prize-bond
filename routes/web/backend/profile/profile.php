<?php

use App\Http\Controllers\Admin\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['as'=> 'admin.profile.', 'prefix' => 'admin/profile' ,'middleware' => ['auth']], function () {
    Route::get('/', [ProfileController::class, 'getData'])->name('page');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
});
