<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tariff\IndexRequest;
use App\Http\Requests\Tariff\StoreRequest;
use App\Http\Requests\Tariff\UpdateRequest;
use App\Models\Tariff;
use App\Repositories\Contracts\TariffInterface;

class TariffController
{
    private TariffInterface $interface;

    public function __construct(TariffInterface $interface)
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
    public function show(Tariff $tariff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tariff $tariff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Tariff $tariff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tariff $tariff)
    {
        //
    }
}
