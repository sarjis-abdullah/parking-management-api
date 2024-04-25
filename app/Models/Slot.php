<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'floor_id',
        'place_id',
        'category_id',
        'identity',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
