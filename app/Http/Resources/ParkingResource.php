<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
//use DNS1D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\DNS2D;


class ParkingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hardcodedClientUrl = "https://parking-management-front.vercel.app";
        $barcodeData = env('CLIENT_URL', $hardcodedClientUrl).'/parking-checkout/' . $this->barcode;
        $barcode = new DNS2D();
        $barcodeData = $barcode->getBarcodePNG($barcodeData, "QRCODE", 4, 4);
        return [
            'id' => $this->id,
            'barcode_image' => $barcodeData,
            'place_id' => $this->place_id,
            'category_id' => $this->category_id,
            'vehicle_id' => $this->vehicle_id,
            'slot_id' => $this->slot_id,
            'barcode' => $this->barcode,
            'in_time' => $this->in_time,
            'out_time' => $this->out_time,
            'status' => $this->status,
            'duration' => $this->duration, //in minute
            'place'  => $this->needToInclude($request, 'p.place') ? new PlaceResource($this->place) : null,
            'category'  => $this->needToInclude($request, 'p.category') ? new CategoryResource($this->category) : null,
            'slot'  => $this->needToInclude($request, 'p.slot') ? new SlotResource($this->slot) : null,
            'payments'  => $this->needToInclude($request, 'p.payments') ? new PaymentResourceCollection($this->payments) : null,
            'payment'  => $this->needToInclude($request, 'p.payment') ? new PaymentResource($this->payment) : null,
            'tariff'  => $this->needToInclude($request, 'p.tariff') ? new TariffResource($this->tariff) : null,
            'floor'  => $this->needToInclude($request, 'p.floor') ? new FloorResource($this->floor) : null,
            'vehicle'  => $this->needToInclude($request, 'p.vehicle') ? new VehicleResource($this->vehicle) : null,
        ];
    }
}
