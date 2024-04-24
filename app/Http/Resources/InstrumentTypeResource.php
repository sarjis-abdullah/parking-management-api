<?php

namespace App\Http\Resources;

class InstrumentTypeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this['isin'],
            'name' => $this['name'],
            'description' => $this['description'],
        ];
    }
}
