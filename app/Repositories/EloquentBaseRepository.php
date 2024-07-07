<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentBaseRepository implements BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * EloquentBaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function findOne($id, $withTrashed = false): ?\ArrayAccess
    {
        $queryBuilder = $this->model;

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        return $queryBuilder->find($id);
    }

    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria, $withTrashed = false): ?\ArrayAccess
    {
        $queryBuilder =  $this->model->where($criteria);

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        if (isset($criteria['eagerLoad'])) {
            $queryBuilder->with($criteria['eagerLoad']);
        }

        return $queryBuilder->first();
    }

    /**
     * @inheritdoc
     */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $limit = !empty($searchCriteria['per_page']) ? (int)$searchCriteria['per_page'] : 50; // it's needed for pagination
        $orderBy = !empty($searchCriteria['order_by']) ? $searchCriteria['order_by'] : 'id';
        $orderDirection = !empty($searchCriteria['order_direction']) ? $searchCriteria['order_direction'] : 'desc';
        $queryBuilder = $this->model->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        if (isset($searchCriteria['eagerLoad'])) {
            $queryBuilder->with($searchCriteria['eagerLoad']);
        }
        if (isset($searchCriteria['rawOrder'])) {
            $queryBuilder->orderByRaw(DB::raw("FIELD(id, {$searchCriteria['id']})"));
        } else {
            $queryBuilder->orderBy($orderBy, $orderDirection);
        }

        if (empty($searchCriteria['withOutPagination'])) {
            return $queryBuilder->paginate($limit);
        } else {
            return $queryBuilder->get();
        }
    }

    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * @inheritdoc
     */
    public function findPartialBy(array $searchCriteria = [], $withTrashed = false)
    {
        // almost a duplicate of findBy. Created a separate function just in case if want to make it intelligent in the future.
        $limit = !empty($searchCriteria['per_page']) ? (int)$searchCriteria['per_page'] : 50; // it's needed for pagination
        $orderBy = !empty($searchCriteria['order_by']) ? $searchCriteria['order_by'] : 'id';
        $orderDirection = !empty($searchCriteria['order_direction']) ? $searchCriteria['order_direction'] : 'desc';
        $queryBuilder = $this->model->where(function ($query) use ($searchCriteria) {
            $this->applyPartialSearchCriteriaInQueryBuilder($query, $searchCriteria, 'like');
        });

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        if (isset($searchCriteria['eagerLoad'])) {
            $queryBuilder->with($searchCriteria['eagerLoad']);
        }
        if (isset($searchCriteria['rawOrder'])) {
            $queryBuilder->orderByRaw(DB::raw("FIELD(id, {$searchCriteria['id']})"));
        } else {
            $queryBuilder->orderBy($orderBy, $orderDirection);
        }

        if (isset($searchCriteria['withOutPagination'])) {
            return $queryBuilder->get();
        } else {
            return $queryBuilder->paginate($limit);
        }
    }

    /**
     * @inheritdoc
     */
    public function findPartialByWithoutPagination(array $searchCriteria = [], $withTrashed = false)
    {
        $searchCriteria['withOutPagination'] = true;
        return $this->findPartialBy($searchCriteria, $withTrashed);
    }

    /**
     * @inheritdoc
     */
    public function save(array $data): \ArrayAccess
    {
        if ($this->model->getTable() != 'users')
        $data['created_by'] = Auth::user()->id;
        return $this->model->create($data);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @inheritdoc
     */
    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
        $fillAbleProperties = $this->model->getFillable();

        foreach ($data as $key => $value) {

            // update only fillAble properties
            if (in_array($key, $fillAbleProperties)) {
                $model->$key = $value;
            }
        }

        // update the model
        $model->save();

        // get updated model from database
        $model = $this->findOne($model->id);

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function findIn(string $key, array $values, $withTrashed = false): ?\IteratorAggregate
    {
        $queryBuilder = $this->model->whereIn($key, $values);

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        return $queryBuilder->get();
    }

    /**
     * @inheritdoc
     */
    public function delete(\ArrayAccess $model): bool
    {
        return $model->delete();
    }

    /**
     * Apply condition on query builder based on search criteria
     *
     * @param Object $queryBuilder
     * @param array $searchCriteria
     * @param string $operator
     * @return mixed
     */
    protected function applySearchCriteriaInQueryBuilder(
        $queryBuilder,
        array $searchCriteria = [],
        string $operator = '='
    ): object
    {
        unset($searchCriteria['include'], $searchCriteria['eagerLoad'], $searchCriteria['rawOrder'], $searchCriteria['detailed'], $searchCriteria['withoutPagination']); //don't need that field for query. only needed for transformer.

        foreach ($searchCriteria as $key => $value) {

            //skip pagination related query params
            if (in_array($key, ['page', 'per_page', 'order_by', 'order_direction'])) {
                continue;
            }

            if ($value == 'null') {
                $queryBuilder->whereNull($key);
            } else {
                if ($value == 'notNull') {
                    $queryBuilder->whereNotNull($key);
                } else {
                    //we can pass multiple params for a filter with commas
                    if (is_array($value)) {
                        $allValues = $value;
                    } else {
                        $allValues = explode(',', $value);
                    }

                    if (count($allValues) > 1) {
                        $queryBuilder->whereIn($key, $allValues);
                    } else {
                        if ($operator == 'like') {
                            $queryBuilder->where($key, $operator, '%' . $value . '%');
                        } else {
                            $queryBuilder->where($key, $operator, $value);
                        }
                    }
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * Apply condition on query builder based on search criteria
     *
     * @param Object $queryBuilder
     * @param array $searchCriteria
     * @param string $operator
     * @return mixed
     */
    protected function applyPartialSearchCriteriaInQueryBuilder(
        $queryBuilder,
        array $searchCriteria = [],
        string $operator = '='
    ) {
        unset($searchCriteria['include'], $searchCriteria['only'], $searchCriteria['eagerLoad'], $searchCriteria['rawOrder'], $searchCriteria['detailed'], $searchCriteria['userInfo'], $searchCriteria['withOutPagination']); //don't need that field for query. only needed for transformer.

        foreach ($searchCriteria as $key => $value) {

            //skip pagination related query params
            if (in_array($key, ['page', 'per_page', 'order_by', 'order_direction'])) {
                continue;
            }

            if ($value == 'null') {
                $queryBuilder->orWhereNull($key);
            } else {
                if ($value == 'notNull') {
                    $queryBuilder->orWhereNotNull($key);
                } else {
                    //we can pass multiple params for a filter with commas
                    $allValues = explode(',', $value);

                    if (count($allValues) > 1) {
                        $queryBuilder->orWhereIn($key, $allValues);
                    } else {
                        if ($operator == 'like') {
                            $queryBuilder->orWhere($key, $operator, '%' . $value . '%');
                        } else {
                            $queryBuilder->orWhere($key, $operator, $value);
                        }
                    }
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * @inheritdoc
     */
    public function updateIn(string $key, array $values, array $data): \IteratorAggregate
    {
        // updated records
        $this->model->whereIn($key, $values)->update($data);

        // return updated records QueryBuilder
        return $this->model->whereIn($key, $values)->get();
    }

    /**
     * get modified fields
     *
     * @param $model
     * @param $data
     * @return array
     */
    public function getModifiedFields($model, $data)
    {
        $fillAbleProperties = $model->getFillable();

        foreach ($data as $key => $value) {
            // update only fillAble properties
            if (in_array($key, $fillAbleProperties)) {
                $model->$key = $value;
            }
        }

        return $model->getDirty();
    }

    /**
     * paginate custom data
     *
     * @param array $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    protected function paginateData($items, $perPage = 15, $page = null, array $options = []) : LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function findByWithoutPagination(array $searchCriteria = [], $withTrashed = false)
    {
        $searchCriteria['withOutPagination'] = true;
        return $this->findBy($searchCriteria, $withTrashed);
    }

    public function updateOrCreate(array $criteria, array $data)
    {
        return $this->model->updateOrCreate($criteria, $data);
    }

    public function deleteBy(array $searchCriteria=[]): bool
    {
        $queryBuilder = $this->model->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });
        return $queryBuilder->delete();
    }
}
