<?php

namespace App\Observers;

use App\Models\Membership;
use App\Models\MembershipType;

class MembershipObserver
{
    /**
     * Handle the Membership "created" event.
     */
    public function created(Membership $membership): void
    {
        // Retrieve the appropriate membership type based on points
        $membershipType = MembershipType::where('min_points', '<=', $membership->points + 1)
            ->orderBy('min_points', 'desc')
            ->first();

        // Update the membership_type_id
        $membership->membership_type_id = $membershipType ? $membershipType->id : null;
    }

    /**
     * Handle the Membership "updated" event.
     */
    public function updated(Membership $membership): void
    {
        dd(67890);

    }

    /**
     * Handle the Membership "deleted" event.
     */
    public function deleted(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "restored" event.
     */
    public function restored(Membership $membership): void
    {
        //
    }

    /**
     * Handle the Membership "force deleted" event.
     */
    public function forceDeleted(Membership $membership): void
    {
        //
    }
}
