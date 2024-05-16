<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use DNS1D;

class ParkingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = DNS1D::getBarcodePNG($this->barcode, 'C39+', 50, 1366);
        return [
            'id' => $this->id,
            'barcode_image' => $image,
            'place_id' => $this->place_id,
            'category_id' => $this->category_id,
            'slot_id' => $this->slot_id,
            'barcode' => $this->barcode,
            'vehicle_no' => $this->vehicle_no,
            'driver_name' => $this->driver_name,
            'driver_mobile' => $this->driver_mobile,
            'in_time' => $this->in_time,
            'out_time' => $this->out_time,
            'payment_method' => $this->payment_method,
            'payable_amount' => $this->payable_amount,
            'paid_amount' => $this->paid_amount,
            'due_amount' => $this->due_amount,
            'status' => $this->status,
            'place'  => $this->needToInclude($request, 'p.place') ? new PlaceResource($this->place) : null,
            'category'  => $this->needToInclude($request, 'p.category') ? new CategoryResource($this->category) : null,
            'slot'  => $this->needToInclude($request, 'p.slot') ? new SlotResource($this->slot) : null,
        ];
    }
}
