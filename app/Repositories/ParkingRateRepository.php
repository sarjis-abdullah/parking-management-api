<?php

namespace App\Repositories;

use App\Repositories\Contracts\InstrumentSupportedRepository;
use App\Repositories\Contracts\ParkingRateInterface;
use App\Repositories\Contracts\UserInterface;

class ParkingRateRepository extends EloquentBaseRepository implements ParkingRateInterface
{

}
