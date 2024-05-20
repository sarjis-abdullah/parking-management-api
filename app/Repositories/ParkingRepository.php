<?php

namespace App\Repositories;

use App\Enums\ParkingStatus;
use App\Enums\SlotStatus;
use App\Exceptions\CustomException;
use App\Exceptions\CustomValidationException;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Payment;
use App\Models\Slot;
use App\Models\Tariff;
use App\Models\Vehicle;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\ParkingInterface;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\UserInterface;
use Exception;
use Illuminate\Support\Facades\DB;

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
            unset($searchCriteria['query']);
        }
        $queryBuilder = $queryBuilder->where('status', '!=', ParkingStatus::checked_out->value);

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
        DB::beginTransaction();
        $data['barcode'] = uniqid();
        $data['in_time'] = now();
        $slot = Slot::find($data['slot_id']);

        $oldVehicle = Vehicle::where('number', $data['vehicle_no'])->first();
        $this->checkVehicleCheckedInToThrowError($oldVehicle);
        $vehicleData = [
            'number' => $data['vehicle_no'],
            'driver_name' => $data['driver_name'] ?? null,
            'driver_mobile' => $data['driver_mobile'] ?? null,
            'category_id' => $data['category_id'],
            'status' => ParkingStatus::checked_in->value,
        ];
        $vehicleId = null;
        if ($oldVehicle instanceof Vehicle){

            if ($oldVehicle->membership_id){
                $vehicleData['points'] = $oldVehicle->points + 5;
            }
            $oldVehicle->update($vehicleData);

            addMembershipTypeToVehicleMembership($oldVehicle);

            $vehicleId = $oldVehicle->id;
        }else {
            $vehicle = Vehicle::create($vehicleData);
            $vehicleId = $vehicle->id;
        }

        $data['vehicle_id'] = $vehicleId;

        if (!isset($data['tariff_id'])){
            $data['tariff_id'] = Tariff::where('default', true)->orderBy('updated_at', 'desc')->first()->id;
        }
        if ($slot->status != SlotStatus::occupied->value){
            Slot::find($data['slot_id'])->update([
                'status' => SlotStatus::occupied->value
            ]);
            $item = parent::save($data);

            DB::commit();
            return $item;
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
//        $data['barcode'] = uniqid();
//        $data['in_time'] = now();
//        $data['status'] = ParkingStatus::checked_in->value;
//        DB::beginTransaction();
//        if (!isset($data['tariff_id'])){
//            $data['tariff_id'] = Tariff::where('default', true)->orderBy('updated_at', 'desc')->first()->id;
//        }
//        $slot = Slot::find($data['slot_id']);
//
//        $vehicle = Vehicle::find($model->vehicle_id);
//        $this->checkVehicleCheckedInToThrowError($vehicle);
//        if ($slot->status != SlotStatus::occupied->value){
//            Slot::find($data['slot_id'])->update([
//                'status' => SlotStatus::occupied->value
//            ]);
//            $item = parent::update($model, $data);
//
//            DB::commit();
//            return $item;
//        } else {
//            throw new CustomValidationException('Slot is already occupied..', 422, [
//                'slot' => ['Slot is already occupied.'],
//            ]);
//        }
    }

    /**
     * @throws CustomValidationException
     */
    protected function checkVehicleCheckedInToThrowError($vehicle): void
    {
        if ($vehicle instanceof Vehicle && $vehicle->status == ParkingStatus::checked_in->value){
            throw new CustomValidationException('The vehicle is already in checked-in.', 422, [
                'status' => ['The vehicle is already in checked-in.'],
            ]);
        }
    }

    /**
     * @throws CustomValidationException
     */
    protected function checkVehicleCheckedOutToThrowError($vehicle): void
    {
        if ($vehicle instanceof Vehicle && $vehicle->status == ParkingStatus::checked_out->value){
            throw new CustomValidationException('The vehicle is already checked-out.', 422, [
                'status' => ['The vehicle is already checked-out.'],
            ]);
        }
    }

    /**
     * @throws CustomValidationException
     */
    public function handleCheckout(\ArrayAccess $model, array $data): \ArrayAccess
    {
        DB::beginTransaction();

        $vehicle = Vehicle::find($model->vehicle_id);
        $this->checkVehicleCheckedOutToThrowError($vehicle);

        Slot::find($model->slot_id)->update([
            'status' => SlotStatus::available->value
        ]);
        Payment::create([
            'method' => $data['payment']['method'],
            'paid_amount' => $data['payment']['paid_amount'],
            'received_by' => auth()->id(),
            'parking_id' => $model->id,
            'paid_by_vehicle_id' => $model->vehicle_id,
        ]);
        $vehicle->update([
            'status' => ParkingStatus::checked_out->value,
        ]);

        $item = parent::update($model, $data);

        DB::commit();

        return $item;
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
