<?php

use App\Http\Services\Systems\Tool\Autoloader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Autoloader::loadFilesRecursivelyInDirs([__DIR__ . '/api/']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user-guest', function (Request $request) {
    return \App\Models\User::all();
});
