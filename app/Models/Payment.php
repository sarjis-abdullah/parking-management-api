<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'payable_amount',
        'paid_amount',
        'due_amount',
        'status',
        'received_by',
        'parking_id',
    ];
}
