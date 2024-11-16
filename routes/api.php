<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ChangeLanguage;
use App\Http\Controllers\Api\V1\Auth\AuthController;

Route::middleware(ChangeLanguage::class)->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        // Forgot password
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        require __DIR__ . '/v1/index.php';

        Route::middleware(['user-access:admin'])->group(function () {
            require __DIR__ . '/v1/admin.php';
        });
    });
});
