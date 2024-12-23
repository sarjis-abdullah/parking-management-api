<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'place_id',
        'category_id',
        'status',
        'default',
        'created_by',
        'updated_by',
        'deleted_by',
        'type',
    ];

    public function parking_rates(): HasMany
    {
        return $this->hasMany(ParkingRate::class);
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

    public function total_active_parking_count(): int
    {
        $query = $this->parking();

        $query->where('out_time', '=', null);

        return $query->count();
    }
}
