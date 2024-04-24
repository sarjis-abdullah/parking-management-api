<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    public function categories()
    {
//        return $this->hasMany('App\Models\Category');
    }
}
