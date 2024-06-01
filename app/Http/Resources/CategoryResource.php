<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends Resource
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
            'description' => $this->description,
//            'place_id' => $this->place_id,
//            'limit_count' => $this->limit_count,
//            'status' => $this->status,
//            'created_by' => $this->created_by,
//            'updated_by' => $this->updated_by,
//            'deleted_by' => $this->deleted_by,
        ];
    }
}
