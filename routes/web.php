<?php

use App\Http\Controllers\ElectricSqlProxyController;
use Illuminate\Support\Facades\Route;

Route::get('/proxy/v1/shape', ElectricSqlProxyController::class)
    ->name('electric.shape.proxy');
