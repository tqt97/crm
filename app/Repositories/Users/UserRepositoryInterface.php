<?php

namespace App\Repositories\Users;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Get List With Pagination
     *
     * @return mixed
     */
    public function getListWithPagination($request);
    public function checkUserExists($request);
    public function findByUuidWithRelation($uuid, array $relations);
}
