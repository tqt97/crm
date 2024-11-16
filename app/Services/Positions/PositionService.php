<?php

namespace App\Services\Positions;

use App\Models\Department;

class PositionService
{
    /**
     * Get List Position with Pagination
     *
     * @param Department $department
     * @param  mixed $request
     * @return mixed
     */
    public function getListPosition(Department $department, $request)
    {
        $positions = $department->positions()->applyLimit($request);

        return $positions;
    }

    /**
     * Create a Position
     *
     * @param Department $department
     * @param  mixed $request
     * @return mixed
     */
    public function createPosition(Department $department, $request)
    {
        $position = $department->positions()->create($request);

        return $position;
    }

    /**
     * Show a Position
     * 
     * @param Department $department
     * @param  mixed $id
     * @return mixed
     */
    public function showPosition(Department $department, $id)
    {
        $position = $department->positions()->find($id);

        return $position;
    }

    /**
     * Update a Position
     *
     * @param Department $department
     * @param  mixed $id
     * @param  mixed $request
     * @return mixed
     */
    public function updatePosition(Department $department, $id, $request)
    {
        $position = $department->positions()->find($id);
        $result = $position->update($request);

        return $result;
    }

    /**
     * Delete Position by Id
     * 
     * @param Department $department
     * @param  mixed $id
     * @return mixed
     */
    public function deletePosition(Department $department, $id)
    {
        $position = $department->positions()->find($id);
        $result = $position->delete();

        return $result;
    }
}
