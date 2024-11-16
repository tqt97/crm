<?php

namespace App\Http\Controllers\Api\V1\Positions;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Http\Resources\PaginationResource;
use App\Http\Requests\Positions\CreateOrUpdateRequest;
use App\Services\Positions\PositionService;
use App\Http\Resources\PositionResource;
use App\Models\Department;

class PositionController extends BaseController
{
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @param Department $department
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Department $department)
    {
        try {
            $positions = App::make(PositionService::class)->getListPosition($department, $request->all());
            $results = new PaginationResource(PositionResource::collection($positions));

            return $this->sendResponse(__(config('erp.msg_retrieved')), $results, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CreateOrUpdateRequest $request
     * @param Department $department
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrUpdateRequest $request, Department $department)
    {
        try {
            $position = App::make(PositionService::class)->createPosition($department, $request->all());

            return $this->sendResponse(__(config('erp.msg_created')), $position, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the specified resource.
     * 
     * @param Department $department
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Department $department, $id)
    {
        try {
            $position = App::make(PositionService::class)->showPosition($department, $id);
            if ($position) {
                return $this->sendResponse(__(config('erp.msg_retrieved')), $position, Response::HTTP_OK);
            }

            return $this->sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param CreateOrUpdateRequest $request
     * @param Department $department
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateOrUpdateRequest $request, Department $department, $id)
    {
        try {
            $position = App::make(PositionService::class)->updatePosition($department, $id, $request->all());
            if ($position) {
                return $this->sendResponse(__(config('erp.msg_updated')), $position, Response::HTTP_OK);
            }

            return $this->sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Soft delete the specified resource from storage.
     * 
     * @param Department $department
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Department $department, $id)
    {
        try {
            App::make(PositionService::class)->deletePosition($department, $id);

            return $this->sendResponse(__(config('erp.msg_deleted')), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
