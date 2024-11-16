<?php

namespace App\Http\Controllers\Api\V1\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

use App\Http\Resources\PaginationResource;
use App\Http\Requests\Products\CategoryRequest;
use App\Http\Controllers\BaseController;
use App\Services\Products\CategoryService;
use App\Http\Resources\CategoryResource;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = App::make(CategoryService::class)->getListCategory($request);
            $categoriesPaginated = new PaginationResource(CategoryResource::collection($categories));
            return $this->sendResponse(__(config('erp.msg_retrieved')), $categoriesPaginated, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = App::make(CategoryService::class)->createCategory($request);
            return $this->sendResponse(__(config('erp.msg_created')), $category, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $category = App::make(CategoryService::class)->showCategory($id);
            if (empty($category)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }

            return $this->sendResponse(__(config('erp.msg_retrieved')), $category, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = App::make(CategoryService::class)->updateCategory($id, $request);
            if (empty($category)) {
                return sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }

            return $this->sendResponse(__(config('erp.msg_updated')), $category, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            App::make(CategoryService::class)->deleteCategory($id);
            return $this->sendResponse(__(config('erp.msg_deleted')), [], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get Category Attribute
     *
     * @param  int $id
     * @return mixed
     */
    public function getAttribute(int $id): mixed
    {
        try {
            $categoryAttributes = App::make(CategoryService::class)->getAttributeWithCategoryId($id);
            if (empty($categoryAttributes)) {
                return $this->sendError(__(config('erp.msg_not_found')), [], Response::HTTP_NOT_FOUND);
            }

            return $this->sendResponse(__(config('erp.msg_retrieved')), $categoryAttributes, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Get Category Attribute
     *
     * @param  int $id
     * @return mixed
     */
    public function getListCategoryForProduct(Request $request): mixed
    {
        try {
            $categories = App::make(CategoryService::class)->getListCategoryForProduct($request);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $categories, Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
