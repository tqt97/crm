<?php

namespace App\Http\Controllers\Api\V1\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\BaseController;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Services\Users\UserService;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Repositories\Permissions\PermissionRepository;

class UserController extends BaseController implements HasMiddleware
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     *  Get the middleware that should be assigned to the controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('user-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('user-store'), only: ['store']),
            new Middleware(PermissionMiddleware::using('user-show'), only: ['show']),
            new Middleware(PermissionMiddleware::using('user-update'), only: ['update']),
            new Middleware(PermissionMiddleware::using('user-delete'), only: ['destroy']),
            // new Middleware(PermissionMiddleware::using('user-profile'), only: ['profile']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userService->getListUser($request);
            $usersPaginated = new PaginationResource(UserResource::collection($users));
            return $this->sendResponse(__(self::MSG_RETRIEVED), $usersPaginated, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->createUserWithProfile($request->all());
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_created')), $user, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($uuid)
    {
        try {
            $user = $this->userService->getUserByUuid($uuid);
            if (empty($user)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            $result = new UserResource($user);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $result, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->updateUserWithProfile($uuid, $request->all());
            if (empty($user)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_updated')), $user, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {
            $this->userService->deleteUserByUuid($uuid);
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_deleted')), [], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show profile
     */
    public function profile()
    {
        try {
            $permissions = App::make(PermissionRepository::class)->getPermissionsWithRoleIsEmployee();
            $profile = new UserProfileResource($permissions);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $profile, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
