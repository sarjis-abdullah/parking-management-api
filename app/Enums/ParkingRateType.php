<?php

namespace App\Enums;

enum ParkingRateType: string
{
    case half_hourly        = '30';
    case hourly         = '60';
    case fixed        = 'fixed';
}
