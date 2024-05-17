<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'limit_count',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function place() : BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
