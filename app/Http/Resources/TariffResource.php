<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TariffResource extends Resource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
//            'place_id' => $this->place_id,
//            'category_id' => $this->id,
            'status' => $this->status,
            'default' => $this->default,
            'parking_rates'  => $this->needToInclude($request, 't.parking_rates') ? new ParkingRateResourceCollection($this->parking_rates) : null,
        ];
    }
}
