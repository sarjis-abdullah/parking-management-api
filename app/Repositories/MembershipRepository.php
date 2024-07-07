<?php

namespace App\Repositories;

use App\Exceptions\CustomValidationException;
use App\Models\MembershipType;
use App\Repositories\Contracts\MembershipInterface;

class MembershipRepository extends EloquentBaseRepository implements MembershipInterface
{
    public function findBy(array $searchCriteria = [], $withTrashed = false, $onlyTrashed = false)
    {
        $queryBuilder = $this->model;

        if (isset($searchCriteria['query'])) {
            $searchCriteria['id'] = $this->model->where('contact_number', 'like', '%' . $searchCriteria['query'] . '%')
                ->orWhereHas('vehicle', function ($query) use ($searchCriteria) {
                    $query->where('number', 'like', '%' . $searchCriteria['query'] . '%');
                })
                ->pluck('id')->toArray();
            unset($searchCriteria['query']);
        }

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
        if ($onlyTrashed){
            $queryBuilder->onlyTrashed();
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
    public function save(array $data): \ArrayAccess
    {
        $type = MembershipType::where('default', true)->orderBy('id', 'desc')->first();
        if ($type == null){
            throw new CustomValidationException('The name field must be an array.', 422, [
                'membership_type' => ['No default membership found, add a default membership type.'],
            ]);
        }else{
            $data['membership_type_id'] = $type->id;
        }
        return parent::save($data);
    }
}
