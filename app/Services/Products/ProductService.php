<?php

namespace App\Services\Products;

use App\Repositories\Products\ProductRepositoryInterface;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Create a Product
     *
     * @param  array $attributes
     * @return mixed
     */
    public function createProduct($request): mixed
    {
        $product =  $this->productRepository->create($request->all());

        return $product;
    }

    /**
     * Get List Product with Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getProducts($request): mixed
    {
        $products = $this->productRepository->getListWithPagination($request);

        return $products;
    }

    /**
     * Show a Product
     *
     * @param  mixed $id
     * @return mixed
     */
    public function showProduct($id): mixed
    {
        $product = $this->productRepository->find($id);

        return $product;
    }

    /**
     * Update a Product
     *
     * @param  mixed $id
     * @param  mixed $attributes
     * @return mixed
     */
    public function updateProduct($id, $request): mixed
    {
        $product = $this->productRepository->update($id, $request->all());

        return $product;
    }

    /**
     * Delete Product by Id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deleteProduct($id): mixed
    {
        $product = $this->productRepository->delete($id);

        return $product;
    }
}
