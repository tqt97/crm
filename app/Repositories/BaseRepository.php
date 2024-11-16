<?php

namespace App\Repositories;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $fieldSearchable = [];

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = App::make(
            $this->getModel()
        );
    }

    /**
     * Get Query
     *
     * @return mixed
     */
    public function getQuery()
    {
        return $this->model->query();
    }

    /**
     * Get all
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all with trashed
     *
     * @return mixed
     */
    public function getAllWithTrash()
    {
        return $this->model::withTrashed()->all();
    }

    /**
     * Get one
     *
     * @return mixed
     */
    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    /**
     * Get one with trashed
     *
     * @return mixed
     */
    public function findWithTrash($id)
    {
        $result = $this->model::withTrashed()->find($id);

        return $result;
    }

    /**
     * Create
     *
     * @param  array $attributes
     * @return mixed
     */
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * Update
     *
     * @param  array $attributes
     * @return mixed
     */
    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    /**
     * Delete
     *
     * @return mixed
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function make(array $with = [])
    {
        if (!empty($with)) {
            $this->model = $this->model->with($with);
        }

        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstBy(array $condition = [], array $select = ['*'], array $with = [])
    {
        $this->make($with);

        if (!empty($select)) {
            return $this->model->where($condition)->select($select)->first();
        }

        return $this->model->where($condition)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getManyBy(array $condition = [], array $select = ['*'], array $with = [])
    {
        $this->make($with);

        if (!empty($select)) {
            return $this->model->where($condition)->select($select)->get();
        }

        return $this->model->where($condition)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }
    /**
     * Update
     *
     * @param  array $attributes
     * @return mixed
     */
    public function updateByUuid($uuid, $attributes = [])
    {
        $result = $this->findByUuid($uuid);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }
}
