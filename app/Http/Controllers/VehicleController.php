<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\IndexRequest;
use App\Http\Requests\Vehicle\StoreRequest;
use App\Http\Requests\Vehicle\UpdateRequest;
use App\Http\Resources\VehicleResource;
use App\Http\Resources\VehicleResourceCollection;
use App\Models\Vehicle;
use App\Repositories\Contracts\VehicleInterface;
use Illuminate\Http\Request;

class VehicleController
{
    private VehicleInterface $interface;

    public function __construct(VehicleInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): VehicleResourceCollection
    {
        $list = $this->interface->findBy($request->all());
        return new VehicleResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new VehicleResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return new VehicleResource($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Vehicle $vehicle)
    {
        $list = $this->interface->update($vehicle, $request->all());
        return new VehicleResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->interface->delete($vehicle);
        return response()->json(null, 204);
    }
}
