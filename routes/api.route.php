<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RefreshTokenController;

Route::prefix('auth')->group(function(){
    Route::middleware('throttle:10,1')->controller(AuthController::class)->group(function(){
        Route::post('register','register');
        Route::post('login','login');
    });
    Route::middleware('throttle:10,1')->middleware('auth:sanctum')->controller(RefreshTokenController::class)->group(function(){
        Route::post('refresh-token','refreshToken')->middleware('check.refresh.token');
    });
});