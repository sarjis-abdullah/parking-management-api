<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'floor_id'
    ];

    public function floor() : BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function slots() : HasMany
    {
        return $this->hasMany(Slot::class);
    }
}
