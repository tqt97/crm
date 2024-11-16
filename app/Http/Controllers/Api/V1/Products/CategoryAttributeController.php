<?php

namespace App\Http\Controllers\Api\V1\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\PaginationResource;
use App\Http\Requests\Products\CreateAttributeRequest;
use App\Services\Products\CategoryAttributeService;
use App\Http\Controllers\BaseController;

class CategoryAttributeController extends BaseController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $category_id)
    {
        try {
            $categoryAttributes = App::make(CategoryAttributeService::class)->getListCategoryAttribute($category_id, $request);
            $categoryAttributesPaginated = new PaginationResource($categoryAttributes);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $categoryAttributesPaginated, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAttributeRequest $request, $category_id)
    {
        DB::beginTransaction();
        try {
            $categoryAttribute = App::make(CategoryAttributeService::class)->createCategoryAttribute($request, $category_id);
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_created')), $categoryAttribute, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($category_id, $id)
    {
        try {
            $categoryAttribute = App::make(CategoryAttributeService::class)->showCategoryAttribute($category_id, $id);
            if (empty($categoryAttribute)) {
                return $this->sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            return $this->sendResponse(__(config('erp.msg_retrieved')), $categoryAttribute, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAttributeRequest $request, $category_id, $id)
    {
        DB::beginTransaction();
        try {
            $categoryAttribute = App::make(CategoryAttributeService::class)->updateCategoryAttribute($category_id, $id, $request);
            if (empty($categoryAttribute)) {
                return $this->sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return $this->sendResponse(__(config('erp.msg_updated')), $categoryAttribute, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category_id, $id)
    {
        try {
            App::make(CategoryAttributeService::class)->deleteCategoryAttribute($id);
            return $this->sendResponse(__('erp.msg_deleted'), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
