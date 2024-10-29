<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashFlow\IndexRequest;
use App\Http\Requests\CashFlow\StoreRequest;
use App\Http\Requests\CashFlow\UpdateRequest;
use App\Http\Resources\CashFlowResource;
use App\Http\Resources\CashFlowResourceCollection;
use App\Models\CashFlow;
use App\Repositories\Contracts\CashFlowInterface;
use Illuminate\Http\Request;

class CashFlowController
{
    private CashFlowInterface $cashFlowInterface;

    public function __construct(CashFlowInterface $cashFlowInterface)
    {
        $this->cashFlowInterface = $cashFlowInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->cashFlowInterface->findBy($request->all());
        return new CashFlowResourceCollection($list);
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
        $list = $this->cashFlowInterface->save($request->all());
        return new CashFlowResource($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function endDay()
    {
        $list = $this->cashFlowInterface->endDay();
        return new CashFlowResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(CashFlow $cashFlow)
    {
        return new CashFlowResource($cashFlow);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashFlow $cashFlow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, CashFlow $cashFlow)
    {
        $list = $this->cashFlowInterface->update($cashFlow, $request->all());
        return new CashFlowResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashFlow $cashFlow)
    {
        //
    }
}
