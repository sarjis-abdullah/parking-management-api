<?php

namespace App\Enums;

enum MembershipType: string {
    case BASIC = 'Basic';
    case STANDARD = 'Standard';
    case PREMIUM = 'Premium';
    case GOLD = 'Gold';
    case PLATINUM = 'Platinum';
    case FAMILY = 'Family';
    case CORPORATE = 'Corporate';
}

function getMembershipPoints(MembershipType $type): int {
    return match ($type) {
        MembershipType::BASIC => 10,
        MembershipType::STANDARD => 20,
        MembershipType::PREMIUM => 30,
        MembershipType::GOLD => 40,
        MembershipType::PLATINUM => 50,
        MembershipType::FAMILY => 60,
        MembershipType::CORPORATE => 70,
    };
}
