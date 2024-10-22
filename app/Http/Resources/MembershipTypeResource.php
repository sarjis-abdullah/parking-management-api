<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipTypeResource extends Resource
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
        'discount_type' => $this->discount_type,
        'discount_amount' => $this->discount_amount,
        'allow_separate_discount' => $this->allow_separate_discount,
        'default' => $this->default,
    ];
    }
}
