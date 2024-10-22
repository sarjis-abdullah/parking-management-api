<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discount\IndexRequest;
use App\Http\Requests\Discount\StoreRequest;
use App\Http\Requests\Discount\UpdateRequest;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\DiscountResourceCollection;
use App\Models\Discount;
use App\Repositories\Contracts\DiscountInterface;

class DiscountController
{
    private DiscountInterface $discount;

    public function __construct(DiscountInterface $discount)
    {
        $this->discount = $discount;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->discount->findBy($request->all());
        return new DiscountResourceCollection($list);
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
        $list = $this->discount->save($request->all());
        return new DiscountResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Discount $discount)
    {
        $list = $this->discount->update($discount, $request->all());
        return new DiscountResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $this->discount->delete($discount);
        return response()->json(null, 204);
    }
}
