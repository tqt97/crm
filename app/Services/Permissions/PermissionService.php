<?php

namespace App\Services\Permissions;

use App\Repositories\Permissions\PermissionRepositoryInterface;

class PermissionService
{
    
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get List Permission with Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListPermission($request): mixed
    {
        return $this->permissionRepository->getListWithPagination($request);
    }

    /**
     * Show a Permission
     *
     * @param  mixed $id
     * @return mixed
     */
    public function showPermission($id): mixed
    {
        return $this->permissionRepository->find($id);
    }

    /**
     * Update a Permission
     *
     * @param  mixed $id
     * @param  mixed $attributes
     * @return mixed
     */
    public function updatePermission($id, $request): mixed
    {
        $attributes = $request->all();
        return $this->permissionRepository->update($id, $attributes);
    }

    /**
     * Delete Permission by Id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deletePermission($id): mixed
    {
        return $this->permissionRepository->delete($id);
    }

    /**
     * Create a Permission
     *
     * @param  mixed $attributes
     * @return mixed
     */
    public function createPermission($attributes): mixed
    {
        return $this->permissionRepository->create($attributes);
    }
}
