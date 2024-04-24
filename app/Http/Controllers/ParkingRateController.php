<?php

namespace App\Http\Controllers;

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
    public function index()
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
    public function store(StoreParkingRateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingRate $parkingRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingRate $parkingRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingRateRequest $request, ParkingRate $parkingRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingRate $parkingRate)
    {
        //
    }
}
