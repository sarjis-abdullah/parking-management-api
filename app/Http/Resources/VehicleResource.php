<?php

namespace App\Http\Resources;

use App\Enums\ParkingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'driver_name' => $this->driver_name,
            'driver_mobile' => $this->driver_mobile,
            'status' => $this->status == ParkingStatus::checked_in ? 'Checked-in' : 'Checked-out',
            'category_id' => $this->category_id,
//            'points' => $this->number,
            'membership'  => $this->needToInclude($request, 'v.membership') ? new MembershipResource($this->membership) : null,
        ];
    }
}
