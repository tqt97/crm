<?php

namespace App\Services\Roles;

use App\Repositories\Roles\RoleRepositoryInterface;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     *  Get List Role with Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListRole($request)
    {
        return $this->roleRepository->getListWithPagination($request);
    }

    /**
     * Create a Role
     *
     * @param  mixed $attributes
     * @return mixed
     */
    public function createRole($attributes)
    {
        return $this->roleRepository->create($attributes);
    }

    /**
     * Show a Role
     *
     * @param  mixed $id
     * @return mixed
     */
    public function showRole($id)
    {
        return $this->roleRepository->find($id);
    }

    /**
     * Update a Role
     *
     * @param  mixed $id
     * @param  mixed $attributes
     * @return mixed
     */
    public function updateRole($id, $attributes)
    {
        return $this->roleRepository->update($id, $attributes);
    }

    /**
     * Delete a Role
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }
}
