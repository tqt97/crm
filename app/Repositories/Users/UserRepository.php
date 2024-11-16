<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
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
        return User::class;
    }
    /**
     * Get List With Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListWithPagination($request)
    {
        $data = $this->model->with(['positions.department'])
            ->filter($request)
            ->sort($request)
            ->applyLimit($request);

        return $data;
    }

    /**
     * Check User Exists
     *
     * @param  mixed $request
     * @return void
     */
    public function checkUserExists($request)
    {
        $data = $this->model->where('email', $request->email)->first();
        if (empty($data)) {
            return false;
        }
        return Hash::check($request->password, $data->password);
    }
    public function findByUuidWithRelation($uuid, array $relations = [])
    {
        $query = $this->model->where('uuid', $uuid);
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }
}
