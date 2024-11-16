<?php

namespace App\Repositories\Roles;

use App\Repositories\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * Get List With Pagination
     */
    public function getListWithPagination($params);
}
