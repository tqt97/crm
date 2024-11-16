<?php

namespace App\Repositories\CategoryAttribute;

use App\Repositories\RepositoryInterface;

interface CategoryAttributeRepositoryInterface extends RepositoryInterface
{

    /**
     * Get List With Pagination
     */
    public function getListWithPagination($category_id, $request);
    public function createCategoryAttribute($request, $category_id);
}
