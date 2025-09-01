<?php

use App\Http\Controllers\V1\ElectricUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('shape')->group(function () {
    Route::get('/users', ElectricUserController::class)->name('shape.users.index');
});
