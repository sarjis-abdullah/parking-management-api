<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\IndexRequest;
use App\Http\Requests\Slot\StoreRequest;
use App\Http\Requests\Slot\UpdateRequest;
use App\Http\Resources\SlotResource;
use App\Http\Resources\SlotResourceCollection;
use App\Models\Slot;
use App\Repositories\Contracts\SlotInterface;

class SlotController
{
    private SlotInterface $interface;

    public function __construct(SlotInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new SlotResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new SlotResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slot $slot)
    {
        return new SlotResource($slot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Slot $slot)
    {
        $list = $this->interface->update($slot, $request->all());
        return new SlotResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slot $slot)
    {
        $this->interface->delete($slot);
        return response()->json(null, 204);
    }
}
