<?php

namespace App\Enums;

enum SlotStatus: String
{
    case available = "available";
    case disabled = "disabled";
    case booked = "booked";
    case occupied = "occupied";
    case reserved = "reserved";
    case outOfService = "out_of_service";
    case pendingApproval = "pending_approval";
    case expired = "expired";
    case underMaintenance = "under_maintenance";
    case emergency = "emergency";
}

