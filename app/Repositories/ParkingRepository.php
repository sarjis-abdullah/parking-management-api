<?php

namespace App\Repositories;

use App\Enums\SlotStatus;
use App\Exceptions\CustomException;
use App\Exceptions\CustomValidationException;
use App\Models\Payment;
use App\Models\Slot;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\ParkingInterface;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\UserInterface;
use Exception;


class ParkingRepository extends EloquentBaseRepository implements ParkingInterface
{
    /*
    * @inheritdoc
    */
    public function findBy(array $searchCriteria = [], $withTrashed = false)
    {
        $queryBuilder = $this->model;

        if(isset($searchCriteria['query'])) {
            $queryBuilder = $queryBuilder->where('vehicle_no', 'like', '%'.$searchCriteria['query'] . '%');
//                ->orWhere('email', 'like', $searchCriteria['query'] . '%')
//                ->orWhere('phone', 'like', $searchCriteria['query'] . '%');
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

        if (empty($searchCriteria['withoutPagination'])) {
            return $queryBuilder->paginate($limit);
        } else {
            return $queryBuilder->get();
        }
    }
    /**
     * @throws Exception
     */
    public function save(array $data): \ArrayAccess
    {
        $data['barcode'] = uniqid();
        $data['in_time'] = now();
        $slot = Slot::find($data['slot_id']);
        $data['status'] = 'in-parking';
        if ($slot->status != SlotStatus::occupied->value){
            Slot::find($data['slot_id'])->update([
                'status' => SlotStatus::occupied->value
            ]);
            return parent::save($data);
        }
        throw new CustomValidationException('The name field must be an array.', 422, [
            'slot' => ['Slot is already occupied.'],
        ]);
    }

    /**
     * @throws CustomValidationException
     */
    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
        $data['barcode'] = uniqid();
        $data['in_time'] = now();
        $data['status'] = 'in-parking';
        $slot = Slot::find($data['slot_id']);
        if ($model->status == 'in-parking'){
            throw new CustomValidationException('The vehicle is already in parking slot.', 422, [
                'status' => ['The vehicle is already in parking slot.'],
            ]);
        }
        elseif ($slot->status != SlotStatus::occupied->value){
            Slot::find($data['slot_id'])->update([
                'status' => SlotStatus::occupied->value
            ]);
            return parent::update($model, $data);
        } else {
            throw new CustomValidationException('Slot is already occupied..', 422, [
                'slot' => ['Slot is already occupied.'],
            ]);
        }
    }

    public function handleCheckout(\ArrayAccess $model, array $data): \ArrayAccess
    {
        Slot::find($model->slot_id)->update([
            'status' => SlotStatus::available->value
        ]);
        Payment::create([
            'method' => $data['payment']['method'],
            'paid_amount' => $data['payment']['paid_amount'],
            'received_by' => auth()->id(),
        ]);
        $data['status'] = 'not-in-parking';
//        $data['received_by'] = auth()->id();
        return parent::update($model, $data);
    }
}
