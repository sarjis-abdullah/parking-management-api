<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'method' => $this->method,
            'payable_amount' => $this->payable_amount,
            'discount_amount' => $this->discount_amount,
            'paid_amount' => $this->paid_amount,
            'due_amount' => $this->due_amount,
            'status' => $this->status,
//            'received_by',
//            'parking_id' => $this->method,
//            'paid_by_vehicle_id',
            'parking'  => $this->needToInclude($request, 'p.parking') ? new ParkingResource($this->parking) : null,
            'transaction_id' => $this->transaction_id,
            'payment_type' => $this->payment_type,
        ];;
    }
}
