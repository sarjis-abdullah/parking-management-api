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

        throw new CustomValidationException("Slot is already occupied.", 400, 'slot_id');
    }
}
