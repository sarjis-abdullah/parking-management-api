<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'driver_name',
        'driver_mobile',
        'status',
        'category_id',
        'points',
    ];

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function parking(): HasMany
    {
        return $this->hasMany(Parking::class);
    }

    public function has_parking(): bool
    {
        $query = $this->parking();

        return $query->count() > 0;
    }
}
