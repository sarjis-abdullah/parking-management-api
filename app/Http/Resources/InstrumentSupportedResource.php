<?php

namespace App\Http\Resources;

use App\Services\Trading\FondService;

class InstrumentSupportedResource extends Resource
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
            'isin' => $this['isin'],
            'wkn' => $this['wkn'],
            'instrument_type_id' => $this['instrument_type_id'],
            'instrument_type'  => $this->needToInclude($request, 'instrument_type') ? new InstrumentTypeResource($this->instrumentType) : null,
            'latest_price' => $this->needToInclude($request, 'latest_price') ? $priceData = app(FondService::class)->getLatestPriceOfTheInstrument($this['isin']) : null,
            'name' => $this['name'],
        ];
    }
}
