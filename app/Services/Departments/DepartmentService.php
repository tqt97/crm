<?php

namespace App\Services\Departments;

use Illuminate\Http\Request;
use App\Repositories\Departments\DepartmentRepositoryInterface;

class DepartmentService
{
    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * Get List Department with Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListDepartment(Request $request)
    {
        return $this->departmentRepository->getListWithPagination($request);
    }

    /**
     * Create a Department
     *
     * @param  mixed $attributes
     * @return mixed
     */
    public function createDepartment(Request $request)
    {
        $data = $this->departmentRepository->create($request->all());
        return $data;
    }

    /**
     * Update a Department
     *
     * @param  mixed $id
     * @param  mixed $attributes
     * @return mixed
     */
    public function updateDepartment($id, Request $request)
    {
        return $this->departmentRepository->update($id, $request->all());
    }

    /**
     * Delete a Department
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deleteDepartment($id)
    {
        return $this->departmentRepository->delete($id);
    }

    /**
     * Show a Department
     *
     * @param  mixed $id
     * @return mixed
     */
    public function showDepartment($id)
    {
        return $this->departmentRepository->find($id);
    }
}
