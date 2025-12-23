<?php

use App\Http\Controllers\V1\ItemController;
use App\Http\Controllers\V1\ItemUserController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('shape')->name('shape.')->group(function () {
    Route::get('/users', UserController::class)->name('users.index');
    Route::get('/items', ItemController::class)->name('items.index');

    Route::prefix('me')->name('me.')->group(function () {
        Route::apiResource('items', ItemUserController::class)->except(['destroy', 'show']);
    });
});
