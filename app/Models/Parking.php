<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'category_id',
        'slot_id',
        'barcode',
        'vehicle_no',
        'driver_name',
        'driver_mobile',
        'in_time',
        'out_time',
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
}
