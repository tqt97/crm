<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get query
     *
     * @return mixed
     */
    public function getQuery();

    /**
     * Get all
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get all with trashed
     *
     * @return mixed
     */
    public function getAllWithTrash();

    /**
     * Get one
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Get one with trashed
     *
     * @return mixed
     */
    public function findWithTrash($id);

    /**
     * Create
     *
     * @param  array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * Update
     *
     * @param  array $attributes
     * @return mixed
     */
    public function update($id, $attributes = []);

    /**
     * Delete
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = []);
    /**
     * Find a single entity by key value.
     *
     * @param array $condition
     * @param array $select
     * @param array $with
     * @return mixed
     */
    public function getFirstBy(array $condition = [], array $select = [], array $with = []);

    /**
     * Find many entity by key value.
     *
     * @param array $condition
     * @param array $select
     * @param array $with
     * @return mixed
     */
    public function getManyBy(array $condition = [], array $select = [], array $with = []);

    /**
     * Find uuid.
     *
     * @param string $uuid
     * @return mixed
     */
    public function findByUuid($uuid);
    /**
     * Update uuid
     *
     * @param  array $attributes
     * @return mixed
     */
    public function updateByUuid($uuid, $attributes = []);
}
