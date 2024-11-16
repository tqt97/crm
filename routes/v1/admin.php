<?php

use App\Http\Controllers\Api\V1\Departments\DepartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Permissions\PermissionController;
use App\Http\Controllers\Api\V1\Users\UserController;
use App\Http\Controllers\Api\V1\Products\CategoryController;
use App\Http\Controllers\Api\V1\Products\CategoryAttributeController;
use App\Http\Controllers\Api\V1\Roles\RoleController;
use App\Http\Controllers\Api\V1\Products\ProductController;
use App\Http\Controllers\Api\V1\Positions\PositionController;
use App\Http\Controllers\Api\V1\Assignments\AssignmentController;

Route::prefix('v1')->group(function () {
    // Category
    Route::get('categories/attributes', [CategoryController::class, 'getListCategoryForProduct']);
    Route::apiResources([
        'users' => UserController::class, // User
        'permissions' => PermissionController::class, // Permission
        'roles' => RoleController::class, // Roles
        'departments' => DepartmentController::class, // Department
        'categories' => CategoryController::class, // Categories
        'category/{category_id}/attributes' => CategoryAttributeController::class, // Attributes
        'departments/{department}/positions' => PositionController::class, // Positions
        'products' => ProductController::class, // Products
    ]);

    // Assignments
    Route::apiResource('assignments', AssignmentController::class)->except(['update', 'delete']);
    Route::put('assignments', [AssignmentController::class, 'update']);
});
