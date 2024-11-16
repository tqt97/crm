<?php

namespace App\Repositories\Departments;

use App\Repositories\RepositoryInterface;

interface DepartmentRepositoryInterface extends RepositoryInterface
{
    /**
     * Get List With Pagination
     */
    public function getListWithPagination($params);
}
