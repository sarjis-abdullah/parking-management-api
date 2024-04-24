<?php

namespace App\Enums;

enum ParkingStatus: string
{
    case available        = 'available';
    case disabled       = 'disabled';
}
