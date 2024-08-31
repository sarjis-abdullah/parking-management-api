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
        'discount_amount',
        'paid_amount',
        'due_amount',
        'status',
        'received_by',
        'parking_id',
        'paid_by_vehicle_id',
        'transaction_id',
    ];

    function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'paid_by_vehicle_id');
    }
}
