<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'driver_name',
        'driver_mobile',
        'status',
        'membership_id',
        'category_id',
        'points',
    ];
}
