<?php

use App\Http\Controllers\Api\V1\ActivityLogs\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Activity Log
Route::get('activity-logs/list', [ActivityLogController::class, 'list']);
