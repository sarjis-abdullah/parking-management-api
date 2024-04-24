<?php

namespace App\Enums;

enum OrderType : string
{
    case delivery        = 'delivery';
    case take_away       = 'take-away';
    case dine_in         = 'dine-in';
}
