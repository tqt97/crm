<?php

namespace App\Http\Controllers\Api\V1\Roles;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Roles\CreateRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Services\Roles\RoleService;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RoleController extends BaseController implements HasMiddleware
{
    /**
     *  Get the middleware that should be assigned to the controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('role-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('role-store'), only: ['store']),
            new Middleware(PermissionMiddleware::using('role-show'), only: ['show']),
            new Middleware(PermissionMiddleware::using('role-update'), only: ['update']),
            new Middleware(PermissionMiddleware::using('role-delete'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $roles = App::make(RoleService::class)->getListRole($request);
            $results = new PaginationResource(RoleResource::collection($roles));
            return $this->sendResponse(__(self::MSG_RETRIEVED), $results);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = App::make(RoleService::class)->createRole($request);
            DB::commit();
            return $this->sendResponse(__(self::MSG_CREATED), $role, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $role = App::make(RoleService::class)->showRole($id) ?? [];
            if ($role) {
                return $this->sendResponse(__(self::MSG_RETRIEVED), $role);
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
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $role = App::make(RoleService::class)->updateRole($id, $request);
            if ($role) {
                DB::commit();
                return $this->sendResponse(__(self::MSG_UPDATED), $role);
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            App::make(RoleService::class)->deleteRole($id);
            DB::commit();
            return $this->sendResponse(__(self::MSG_DELETED), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }
}
