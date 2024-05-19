<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


enum SubscriptionType: string {
    case BASIC = 'Basic';
    case STANDARD = 'Standard';
    case PREMIUM = 'Premium';
    case GOLD = 'Gold';
    case PLATINUM = 'Platinum';
    case FAMILY = 'Family';
    case CORPORATE = 'Corporate';
}
class MembershipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_points',
    ];
}
