<?php

namespace App\Repositories\Permissions;

use App\Models\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return Permission::class;
    }

    /**
     * Get List With Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListWithPagination($request)
    {
        $results = $this->model->filter($request)->sort($request)->applyLimit($request);
        return $results;
    }
 
    /**
     * Get Permissions With Role Employee
     *
     * @param  mixed $request
     * @return void
     */
    public function getPermissionsWithRoleIsEmployee()
    {
        $permissions = $this->model->select('permissions.id', 'permissions.name', 'permissions.guard_name')
            ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'role_has_permissions.role_id')
            ->where('roles.name', 'Employee')->get()->toArray();
        return $permissions;
    }
}
