<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipResource extends Resource
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
            'name' => $this->name,
            'contact_number' => $this->contact_number,
            'vehicle_id' => $this->vehicle_id,
            'membership_type_id' => $this->membership_type_id,
            'membership_type'  => $this->needToInclude($request, 'm.mt') ? new MembershipTypeResource($this->membershipType) : null,
            'vehicle'  => $this->needToInclude($request, 'm.vehicle') ? new VehicleResource($this->vehicle) : null,
        ];
    }
}
