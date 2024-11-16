<?php

namespace App\Repositories\Permissions;

use App\Repositories\RepositoryInterface;

interface PermissionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get List With Pagination
     */
    public function getListWithPagination($params);
    public function getPermissionsWithRoleIsEmployee();
}
