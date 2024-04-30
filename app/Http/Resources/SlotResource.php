<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends Resource
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
            'name' => $this->name ,
            'remarks'=> $this->remarks,
            'status'=> $this->status,
            'created_by'=> $this->created_by,
            'createdByUser'  => $this->needToInclude($request, 'p.createdByUser') ? new UserResource($this->createdByUser) : null,
            'floor'  => $this->needToInclude($request, 's.floor') ? new FloorResource($this->floor) : null,
            'category'  => $this->needToInclude($request, 's.category') ? new CategoryResource($this->category) : null,
            'place'  => $this->needToInclude($request, 's.place') ? new PlaceResource($this->place) : null,
            'updated_by'=> $this->id,
            'deleted_by'=> $this->id,
        ];
    }
}
