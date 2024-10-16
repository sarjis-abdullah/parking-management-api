<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'place_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function place() : BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function parking(): HasMany
    {
        return $this->hasMany(Parking::class);
    }
    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    public function has_parking(): bool
    {
        $query = $this->parking();

        return $query->count() > 0;
    }

    public function slotsOrderByBlockId(): HasMany
    {
        return $this->slots()->orderByDesc('block_id');
    }
}
