<?php

namespace App\Http\Resources;

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
            'driver_name' => $this->number,
            'driver_mobile' => $this->number,
            'status' => $this->number,
            'category_id' => $this->number,
            'points' => $this->number,
            'membership'  => $this->needToInclude($request, 'v.membership') ? new MembershipResource($this->membership) : null,
        ];
    }
}
