<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\IndexRequest;
use App\Http\Requests\Parking\StoreRequest;
use App\Http\Requests\Parking\UpdateRequest;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Parking $parking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parking $parking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Parking $parking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parking $parking)
    {
        //
    }
}
