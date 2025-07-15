<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);


    Route::prefix('auth')->middleware(['auth:api'])->group(function () {
        Route::put('/profile/update', [AuthController::class, 'updateProfile']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
