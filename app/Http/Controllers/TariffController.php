<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tariff\IndexRequest;
use App\Http\Requests\Tariff\StoreRequest;
use App\Http\Requests\Tariff\UpdateRequest;
use App\Http\Resources\TariffResource;
use App\Http\Resources\TariffResourceCollection;
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
        $list = $this->interface->findBy($request->all());
        return new TariffResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new TariffResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tariff $tariff)
    {
        return new TariffResource($tariff);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Tariff $tariff)
    {
        $list = $this->interface->update($tariff, $request->all());
        return new TariffResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tariff $tariff)
    {
        $this->interface->delete($tariff);
        return response()->json(null, 204);
    }
}
