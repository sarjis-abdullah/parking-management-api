<?php

namespace App\Http\Controllers;

use App\Http\Requests\Place\IndexRequest;
use App\Http\Requests\Place\StoreRequest;
use App\Http\Requests\Place\UpdateRequest;
use App\Http\Resources\PlaceResource;
use App\Http\Resources\PlaceResourceCollection;
use App\Models\Place;
use App\Repositories\Contracts\PlaceInterface;

class PlaceController
{
    private PlaceInterface $interface;

    public function __construct(PlaceInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new PlaceResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new PlaceResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        return new PlaceResource($place);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Place $place)
    {
        $list = $this->interface->update($place, $request->all());
        return new PlaceResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $this->interface->delete($place);
        return response()->json(null, 204);
    }
}
