<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }
}
