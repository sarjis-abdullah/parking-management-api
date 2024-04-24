<?php

namespace App\Repositories\Contracts;

interface BaseRepository
{
    /**
     * find a resource by id
     *
     * @param mixed $id
     * @return \ArrayAccess|null
     */
    public function findOne($id): ?\ArrayAccess;

    /**
     * find a resource by criteria
     *
     * @param array $criteria
     * @return \ArrayAccess | null
     */
    public function findOneBy(array $criteria): ?\ArrayAccess;

    /**
     * Search All resources
     *
     * @param array $searchCriteria
     * @return mixed
     */
    public function findBy(array $searchCriteria = []);

    /**
     * Search All resources partially
     *
     * @param array $searchCriteria
     * @return mixed
     */
    public function findPartialBy(array $searchCriteria = []);

    /**
     * Search All resources by any values of a key
     *
     * @param string $key
     * @param array $values
     * @return \IteratorAggregate | null
     */
    public function findIn(string $key, array $values): ?\IteratorAggregate;

    /**
     * save a resource
     *
     * @param array $data
     * @return \ArrayAccess
     */
    public function save(array $data): \ArrayAccess;

    public function insert(array $data);

    /**
     * update a resource
     *
     * @param \ArrayAccess $model
     * @param array $data
     * @return \ArrayAccess
     */
    public function update(\ArrayAccess $model, array $data): \ArrayAccess;

    /**
     * delete a resource
     *
     * @param \ArrayAccess $model
     * @return bool
     */
    public function delete(\ArrayAccess $model): bool;

    /**
     * @param array $searchCriteria
     * @return bool
     */
    public function deleteBy(array $searchCriteria = []): bool;

    /**
     * updated records by specific key and values
     *
     * @param string $key
     * @param array $values
     * @param array $data
     * @return \IteratorAggregate
     */
    public function updateIn(string $key, array $values, array $data): ?\IteratorAggregate;

    /**
     * Search All resources without pagination (very expensive)
     *
     * @param array $searchCriteria
     * @param bool $withTrashed
     * @return mixed
     */
    public function findByWithoutPagination(array $searchCriteria = [], $withTrashed = false);

    /**
     * Search All resources partially and returns without pagination (very expensive)
     *
     * @param array $searchCriteria
     * @param bool $withTrashed
     * @return mixed
     */
    public function findPartialByWithoutPagination(array $searchCriteria = [], $withTrashed = false);
}
