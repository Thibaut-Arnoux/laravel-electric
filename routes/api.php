<?php

use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('shape')->group(function () {
    Route::get('/users', UserController::class)->name('shape.users.index');
});
