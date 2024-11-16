<?php

namespace App\Http\Controllers\Api\V1\Permissions;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Permissions\CreateRequest;
use App\Http\Requests\Permissions\UpdateRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\PermissionResource;
use App\Services\Permissions\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends BaseController implements HasMiddleware
{
    /**
     *  Get the middleware that should be assigned to the controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('permission-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('permission-store'), only: ['store']),
            new Middleware(PermissionMiddleware::using('permission-show'), only: ['show']),
            new Middleware(PermissionMiddleware::using('permission-update'), only: ['update']),
            new Middleware(PermissionMiddleware::using('permission-delete'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        try {
            $permisions = App::make(PermissionService::class)->getListPermission($request);
            $results = new PaginationResource(PermissionResource::collection($permisions));
            return $this->sendResponse(__(self::MSG_RETRIEVED), $results, Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): Response
    {
        DB::beginTransaction();
        try {
            $permission = App::make(PermissionService::class)->createPermission($request);
            DB::commit();
            return $this->sendResponse(__(self::MSG_CREATED), $permission, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id): Response
    {
        try {
            $permission = App::make(PermissionService::class)->showPermission($id);
            if ($permission) {
                return $this->sendResponse(__(self::MSG_RETRIEVED), $permission);
            }

            return $this->sendError(__(self::MSG_NOT_FOUND), [], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id): Response
    {
        DB::beginTransaction();
        try {
            $permission = App::make(PermissionService::class)->updatePermission($id, $request);
            if ($permission) {
                DB::commit();
                return $this->sendResponse(__(self::MSG_UPDATED), $permission);
            }
            return $this->sendError(__(self::MSG_NOT_FOUND), [], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): Response
    {
        DB::beginTransaction();
        try {
            App::make(PermissionService::class)->deletePermission($id);
            DB::commit();
            return $this->sendResponse(__(self::MSG_DELETED), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }
}
