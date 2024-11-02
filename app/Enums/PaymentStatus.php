<?php

namespace App\Enums;

enum PaymentStatus : string
{
    case success        = 'success';
    case unpaid        = 'unpaid';
    case failed        = 'failed';
    case declined        = 'declined';
    case pending        = 'pending';
    case canceled        = 'canceled';
    case processing        = 'processing';
    case complete        = 'complete';
}
