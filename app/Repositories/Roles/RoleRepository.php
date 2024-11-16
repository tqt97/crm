<?php

namespace App\Repositories\Roles;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
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
        return Role::class;
    }

    /**
     * Show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        return $this->model->with('permissions')->where('id', $id)->get();
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
}
