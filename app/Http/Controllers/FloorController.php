<?php

namespace App\Http\Controllers;

use App\Http\Requests\Floor\IndexRequest;
use App\Http\Requests\Floor\StoreRequest;
use App\Http\Requests\Floor\UpdateRequest;
use App\Http\Resources\FloorResource;
use App\Http\Resources\FloorResourceCollection;
use App\Models\Floor;
use App\Repositories\Contracts\FloorInterface;

class FloorController
{
    private FloorInterface $interface;

    public function __construct(FloorInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new FloorResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new FloorResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Floor $floor)
    {
        return new FloorResource($floor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Floor $floor)
    {
        $list = $this->interface->update($floor, $request->all());
        return new FloorResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor)
    {
        $this->interface->delete($floor);
        return response()->json(null, 204);
    }
}
