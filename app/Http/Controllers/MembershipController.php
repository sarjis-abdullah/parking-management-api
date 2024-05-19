<?php

namespace App\Http\Controllers;

use App\Http\Requests\Membership\IndexRequest;
use App\Http\Requests\Membership\StoreRequest;
use App\Http\Requests\Membership\UpdateRequest;
use App\Http\Resources\MembershipResource;
use App\Http\Resources\MembershipResourceCollection;
use App\Models\Membership;
use App\Repositories\Contracts\MembershipInterface;
use Illuminate\Http\Request;

class MembershipController
{
    private MembershipInterface $interface;

    public function __construct(MembershipInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new MembershipResourceCollection($list);
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
        $list = $this->interface->save($request->all());
        return new MembershipResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        return new MembershipResource($membership);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Membership $membership)
    {
        $list = $this->interface->update($membership, $request->all());
        return new MembershipResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        $this->interface->delete($membership);
        return response()->json(null, 204);
    }
}
