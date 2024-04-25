<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParkingRate\IndexRequest;
use App\Http\Requests\ParkingRate\StoreRequest;
use App\Http\Requests\ParkingRate\UpdateRequest;
use App\Http\Resources\ParkingRateResource;
use App\Http\Resources\ParkingRateResourceCollection;
use App\Models\ParkingRate;
use App\Repositories\Contracts\ParkingRateInterface;

class ParkingRateController
{
    private ParkingRateInterface $interface;

    public function __construct(ParkingRateInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new ParkingRateResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new ParkingRateResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingRate $parkingRate)
    {
        return new ParkingRateResource($parkingRate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ParkingRate $parkingRate)
    {
        $list = $this->interface->update($parkingRate, $request->all());
        return new ParkingRateResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingRate $parkingRate)
    {
        $this->interface->delete($parkingRate);
        return response()->json(null, 204);
    }
}
