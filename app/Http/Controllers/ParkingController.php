<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\CheckoutRequest;
use App\Http\Requests\Parking\IndexRequest;
use App\Http\Requests\Parking\StoreRequest;
use App\Http\Requests\Parking\UpdateRequest;
use App\Http\Resources\ParkingResource;
use App\Http\Resources\ParkingResourceCollection;
use App\Models\Parking;
use App\Repositories\Contracts\ParkingInterface;

class ParkingController
{
    private ParkingInterface $interface;

    public function __construct(ParkingInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new ParkingResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new ParkingResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Parking $parking)
    {
        return new ParkingResource($parking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Parking $parking)
    {
        $list = $this->interface->update($parking, $request->all());
        return new ParkingResource($list);
    }
    /**
     * Update the specified resource in storage.
     */
    public function handleCheckout(CheckoutRequest $request, Parking $parking)
    {
        $list = $this->interface->handleCheckout($parking, $request->all());
        return new ParkingResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parking $parking)
    {
        $this->interface->delete($parking);
        return response()->json(null, 204);
    }
}
