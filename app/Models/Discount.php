<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'promo_code',
        'valid_for_hours',
        'valid_after_visits',
        'start_date',
        'end_date',
        'is_active',
        'allow_separate_discount',
    ];
}
