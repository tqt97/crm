<?php

namespace App\Repositories\Products;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Get List With Pagination
     */
    public function getListWithPagination($request);
    public function getProductById(int $id);
}
