<?php

namespace App\Enums;

enum ParkingRateType: string
{
    case half_hourly        = 'half_hourly';
    case hourly         = 'hourly';
    case fixed        = 'fixed';
}
