<?php

namespace App\Repositories;

use App\Enums\ParkingStatus;
use App\Exceptions\CustomValidationException;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Vehicle;
use App\Repositories\Contracts\VehicleInterface;

class VehicleRepository extends EloquentBaseRepository implements VehicleInterface
{
    /*
    * @inheritdoc
    */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $queryBuilder = $this->model;

        if (isset($searchCriteria['query'])) {
            $queryBuilder = $queryBuilder->where('number', 'like', '%' . $searchCriteria['query'] . '%');
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

    public function save(array $data): \ArrayAccess
    {
        if (isset($data['membership_id'])) {
            $data['points'] = 5;
        }
        $vehicle = parent::save($data);

        addMembershipTypeToVehicleMembership($vehicle);

        return parent::save($data);
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
        } else if (isset($data['membership_id'])) {
            $data['points'] = 5;

        }
        $vehicle = parent::update($model, $data);

        addMembershipTypeToVehicleMembership($vehicle);

        return $vehicle;
    }
}

function addMembershipTypeToVehicleMembership($vehicle): void
{
    if ($vehicle instanceof Vehicle && $vehicle->membership_id) {
        $membershipType = MembershipType::where('min_points', '<=', $vehicle->points)
            ->orderBy('min_points', 'desc')
            ->first();

        // Update the membership_type_id
        $membership = Membership::find($vehicle->membership_id);
        $membership->membership_type_id = $membershipType ? $membershipType->id : null;
        $membership->save();
    }
}
