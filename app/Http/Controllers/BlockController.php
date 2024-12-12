<?php

namespace App\Http\Controllers;

use App\Http\Requests\Block\IndexRequest;
use App\Http\Requests\Block\StoreRequest;
use App\Http\Requests\Block\UpdateRequest;
use App\Http\Resources\BlockResource;
use App\Http\Resources\BlockResourceCollection;
use App\Models\Block;
use App\Repositories\Contracts\BlockInterface;
use Illuminate\Http\Request;

class BlockController
{
    private BlockInterface $blockInterface;

    public function __construct(BlockInterface $blockInterface)
    {
        $this->blockInterface = $blockInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->blockInterface->findBy($request->all());
        return new BlockResourceCollection($list);
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
        $list = $this->blockInterface->save($request->all());
        return new BlockResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Block $block)
    {
        $list = $this->blockInterface->update($block, $request->all());
        return new BlockResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $block->delete();
        return response()->json(null, 204);
    }
}
