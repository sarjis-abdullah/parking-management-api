<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends Resource
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
            'email' => $this->email,
            'status' => $this->status,
            'roles' => new RoleResourceCollection($this->roles),
//            'roles' => $this->when($this->needToInclude($request, 'user.roles'), function () {
//                return new RoleResourceCollection($this->roles);
//            }),
        ];
    }
}
