<?php

use App\Http\Controllers\Auth\ElectricAuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ElectricAuthenticatedSessionController::class, 'store'])
    ->name('login')
    ->middleware('guest');
