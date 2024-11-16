<?php

namespace App\Http\Controllers\Api\V1\Departments;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Departments\DepartmentRequest;
use App\Http\Resources\PaginationResource;
use App\Services\Departments\DepartmentService;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DepartmentController extends BaseController
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('department-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('department-store'), only: ['store']),
            new Middleware(PermissionMiddleware::using('department-show'), only: ['show']),
            new Middleware(PermissionMiddleware::using('department-update'), only: ['update']),
            new Middleware(PermissionMiddleware::using('department-delete'), only: ['destroy']),
        ];
    }

    /**
     * Get a list of Departments based on the specified conditions in the request.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $list = App::make(DepartmentService::class)->getListDepartment($request);
            $departmentsPaginated = new PaginationResource($list);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $departmentsPaginated, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Create a new department with the specified data from the request.
     *
     * @param App\Http\Requests\DepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DepartmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = App::make(DepartmentService::class)->createDepartment($request);
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_created')), $data, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Get the details of a specific department.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $data = App::make(DepartmentService::class)->showDepartment($id);
            if (empty($data)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            return $this->sendResponse(__(config('erp.msg_retrieved')), $data, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified department with new data.
     *
     * @param int $id
     * @param \App\Http\Requests\DepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, DepartmentRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = App::make(DepartmentService::class)->updateDepartment($id, $request);
            if (empty($data)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_updated')), $data, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Remove the specified department from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            App::make(DepartmentService::class)->deleteDepartment($id);
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_deleted')), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
