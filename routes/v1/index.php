<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\Users\UserController;
use App\Http\Controllers\Api\V1\Auth\AuthController;

Route::post('logout', [AuthController::class, 'logout']);
Route::prefix('v1')->group(function () {
    Route::post('change-password', [AuthController::class, 'changePassword']);
    // Menu
    Route::get('menus/list-translation', [MenuController::class, 'listTranslation'])->name('menus.list-translation');
    // User
    Route::get('users/profile', [UserController::class, 'profile'])->middleware(['append-request']);
});
