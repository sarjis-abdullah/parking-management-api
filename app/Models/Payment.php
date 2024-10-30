<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'payable_amount',
        'discount_amount',
        'membership_discount',
        'paid_amount',
        'due_amount',
        'status',
        'received_by',
        'parking_id',
        'paid_by_vehicle_id',
        'transaction_id',
        'payment_type',
        'paid_now',
        'date',
    ];

    function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'paid_by_vehicle_id');
    }
    function parking(): BelongsTo
    {
        return $this->belongsTo(Parking::class, 'parking_id');
    }
}
