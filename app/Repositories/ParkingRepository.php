<?php

namespace App\Repositories;

use App\Enums\SlotStatus;
use App\Exceptions\CustomException;
use App\Exceptions\CustomValidationException;
use App\Models\Slot;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\ParkingInterface;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\UserInterface;
use Exception;


class ParkingRepository extends EloquentBaseRepository implements ParkingInterface
{
    /**
     * @throws Exception
     */
    public function save(array $data): \ArrayAccess
    {
        $data['barcode'] = uniqid();
        $data['in_time'] = now();
        $slot = Slot::find($data['slot_id']);
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

    public function update(\ArrayAccess $model, array $data): \ArrayAccess
    {
        Slot::find($model->slot_id)->update([
            'status' => SlotStatus::available->value
        ]);
        return parent::update($model, $data);
    }
}
