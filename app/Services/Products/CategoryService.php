<?php

namespace App\Services\Products;

use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get List Category with Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListCategory($request): mixed
    {
        return $this->categoryRepository->getListWithPagination($request);
    }

    /**
     * Create a Category
     *
     * @param  mixed $request
     * @return mixed
     */
    public function createCategory($request): mixed
    {
        $attributes = $request->all();
        return $this->categoryRepository->create($attributes);
    }

    /**
     * Show a Category
     *
     * @param  mixed $id
     * @return mixed
     */
    public function showCategory($id): mixed
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Update a Category
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return mixed
     */
    public function updateCategory($id, $request): mixed
    {
        return $this->categoryRepository->update($id, $request->all());
    }

    /**
     * Delete Category by Id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deleteCategory($id): mixed
    {
        return $this->categoryRepository->delete($id);
    }

    /**
     * Get attributes by category id
     *
     * @param  mixed $id
     * @return void
     */
    public function getAttributeWithCategoryId(int $id)
    {
        return $this->categoryRepository->getAttributeWithCategoryId($id);
    }

    /**
     * Get list category and child information for add new product
     *
     * @param  mixed $request
     * @return void
     */
    public function getListCategoryForProduct($request)
    {
        $data = $this->categoryRepository->getListCategoryForProduct($request);
        return $data;
    }
}
