<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'category_id',
        'slot_id',
        'floor_id',
        'tariff_id', //
        'vehicle_id',
        'barcode',
        'in_time',
        'out_time',
        'duration',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'vehicle_id', 'vehicle_id');
    }
}
