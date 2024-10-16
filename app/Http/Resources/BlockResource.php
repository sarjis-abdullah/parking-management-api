<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends Resource
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
            'floor'  => $this->needToInclude($request, 'b.floor') ? new FloorResource($this->floor) : null,
            'slots'  => $this->needToInclude($request, 'b.slots') ? new FloorResource($this->slots) : null,
        ];
    }
}
