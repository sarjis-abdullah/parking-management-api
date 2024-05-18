<?php

namespace App\Enums;

enum ParkingStatus: String
{
    case in_parking = 'in-parking';
    case checked_in = 'checked_in';
    case checked_out = 'checked_out';
    case not_in_parking = 'not-in-parking';
}

