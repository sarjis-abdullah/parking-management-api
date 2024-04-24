<?php

namespace App\Enums;

enum ParkingRateDuration: string
{
    case thirty_minute        = 'thirty_minute';
    case sixty_minute         = 'sixty_minute';
    case ninety_minute        = 'ninety_minute';
}
