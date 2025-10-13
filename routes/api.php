<?php

use App\Http\Controllers\ElectricSqlProxyController;
use Illuminate\Support\Facades\Route;

Route::prefix('shape')->group(function () {
    Route::get('/users', ElectricSqlProxyController::class)->name('shape.users.index');
});
