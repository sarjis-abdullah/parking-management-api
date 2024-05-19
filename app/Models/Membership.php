<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'membership_type_id',
        'points',
    ];

    public function membershipType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }
}
