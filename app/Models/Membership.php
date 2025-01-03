<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'membership_type_id',
        'points',
        'vehicle_id',
        'offer_type',
        'discount_percentage',
        'discount_amount',
        'active',
    ];

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parkings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Parking::class, 'vehicle_id', 'vehicle_id');
    }
}
