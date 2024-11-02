<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case cash        = 'cash';
    case ssl_commerz        = 'ssl_commerz';
    case none        = 'none';
    case mixed        = 'cash+ssl_commerz';
}
