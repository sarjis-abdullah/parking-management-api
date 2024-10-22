<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'discount_type' => $this->type,
            'amount' => $this->amount,
            'promo_code' => $this->promo_code,
            'valid_for_hours' => $this->valid_for_hours,
            'valid_after_visits' => $this->valid_after_visits,
            'is_active' => $this->is_active,
            'allow_separate_discount' => $this->allow_separate_discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
