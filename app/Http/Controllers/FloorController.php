<?php

namespace App\Http\Controllers;

use App\Http\Requests\Floor\IndexRequest;
use App\Http\Requests\Floor\StoreRequest;
use App\Http\Requests\Floor\UpdateRequest;
use App\Models\Floor;
use App\Repositories\Contracts\FloorInterface;

class FloorController
{
    private FloorInterface $floor;

    public function __construct(FloorInterface $floor)
    {
        $this->floor = $floor;
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
    public function show(Floor $floor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Floor $floor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Floor $floor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor)
    {
        //
    }
}
