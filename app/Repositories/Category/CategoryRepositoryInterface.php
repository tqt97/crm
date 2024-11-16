<?php

namespace App\Repositories\Category;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{

    /**
     * Get List With Pagination
     */
    public function getListWithPagination($params);

    /**
     * Get Attribute With Category Id
     *
     * @param  mixed $id
     * @return void
     */
    public function getAttributeWithCategoryId(int $id);

    /**
     * Get list category for case add product
     *
     * @param  mixed $request
     * @return void
     */
    public function getListCategoryForProduct($request);
}
