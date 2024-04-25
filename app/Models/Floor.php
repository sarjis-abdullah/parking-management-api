<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'place_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
