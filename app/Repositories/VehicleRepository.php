<?php

namespace App\Repositories;

use App\Enums\ParkingStatus;
use App\Exceptions\CustomValidationException;
use App\Models\Membership;
use App\Repositories\Contracts\VehicleInterface;

class VehicleRepository extends EloquentBaseRepository implements VehicleInterface
{
    /*
    * @inheritdoc
    */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $queryBuilder = $this->model;

        if(isset($searchCriteria['query'])) {
            $queryBuilder = $queryBuilder->where('number', 'like', '%'.$searchCriteria['query'] . '%');
            unset($searchCriteria['query']);
        }
//        $queryBuilder = $queryBuilder->where('status', '!=', ParkingStatus::checked_out->value);

        $queryBuilder = $queryBuilder->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });

        $limit = !empty($searchCriteria['per_page']) ? (int)$searchCriteria['per_page'] : 15;
        $orderBy = !empty($searchCriteria['order_by']) ? $searchCriteria['order_by'] : 'id';
        $orderDirection = !empty($searchCriteria['order_direction']) ? $searchCriteria['order_direction'] : 'desc';
        $queryBuilder->orderBy($orderBy, $orderDirection);

        if ($withTrashed) {
            $queryBuilder->withTrashed();
        }

        if (empty($searchCriteria['withoutPagination'])) {
            return $queryBuilder->paginate($limit);
        } else {
            return $queryBuilder->get();
        }
    }

    /**
     * @throws CustomValidationException
     */
    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
        if ($model->membership_id && isset($data['membership_id'])) {
            unset($data['membership_id']);
            throw new CustomValidationException('This vehicle is already added to membership list.', 422, [
                'membership' => ['This vehicle is already added to membership list.'],
            ]);
        }else if (isset($data['membership_id'])){
            $data['points'] = 5;

        }
        return parent::update($model, $data);
    }
}
