<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashFlowResource extends JsonResource
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
            'starting_cash' => $this->starting_cash,
            'income' => $this->income,
            'expenses' => $this->expenses,
            'ending_cash' => $this->ending_cash,
            'date' => $this->date, // Format as YYYY-MM-DD
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
