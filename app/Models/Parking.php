<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'category_id',
        'slot_id',
        'barcode',
        'vehicle_no',
        'driver_name',
        'driver_mobile',
        'in_time',
        'out_time',
        'payment_method',
        'payable_amount',
        'paid_amount',
        'due_amount',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
