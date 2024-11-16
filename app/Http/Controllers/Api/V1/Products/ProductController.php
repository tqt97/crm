<?php

namespace App\Http\Controllers\Api\V1\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\ProductResource;
use App\Services\Products\ProductService;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = App::make(ProductService::class)->getProducts($request);
            $results = new PaginationResource(ProductResource::collection($products));
            return $this->sendResponse(__(config('erp.msg_retrieved')), $results);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = App::make(ProductService::class)->createProduct($request);
            $product = new ProductResource($product);
            return $this->sendResponse(__(config('erp.msg_created')), $product, Response::HTTP_CREATED);
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
            $product = App::make(ProductService::class)->showProduct($id);
            $product = new ProductResource($product);
            return $this->sendResponse(__(config('erp.msg_retrieved')), $product);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = App::make(ProductService::class)->updateProduct($id, $request);
            $product = new ProductResource($product);
            return $this->sendResponse(__(config('erp.msg_updated')), $product);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            App::make(ProductService::class)->deleteProduct($id);
            return $this->sendResponse(__(config('erp.msg_deleted')));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
