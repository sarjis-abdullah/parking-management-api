<?php

namespace App\Repositories;

use App\Models\ParkingRate;
use App\Models\Tariff;
use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\PlaceInterface;
use App\Repositories\Contracts\TariffInterface;
use App\Repositories\Contracts\UserInterface;

class TariffRepository extends EloquentBaseRepository implements TariffInterface
{
    public function save(array $data): \ArrayAccess
    {
        if (!isset($data['default']) || !$data['default']){
            $oldTariff = Tariff::where('default', true)->first();
            if ($oldTariff == null){
                $data['default'] = true;
            }
        }
        $tariff = parent::save($data);

        $payment_rates = array_map(function ($item) use (&$tariff) {
            $item['tariff_id']= $tariff->id;
            return $item;
        }, $data['payment_rates']);

        ParkingRate::insert($payment_rates);
        return $tariff;
    }
}
