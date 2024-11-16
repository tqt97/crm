<?php

namespace App\Http\Controllers\Api\V1\ActivityLogs;

use App\Http\Controllers\BaseController;
use App\Services\ActivityLogs\ActivityLogService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use App\Http\Resources\ActivityLogResource;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ActivityLogController extends BaseController implements HasMiddleware
{
    /**
     *  Get the middleware that should be assigned to the controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('activity_log-list'), only: ['list']),
        ];
    }

    public function list()
    {
        $list = App::make(ActivityLogService::class)->getActivityLogList();
        return $this->sendResponse(null, ActivityLogResource::collection($list));
    }
}
