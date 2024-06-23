<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function vehicleCategories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
    public function createdByUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
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
