<?php

namespace App\Http\Controllers;

use App\Http\Requests\MembershipType\IndexRequest;
use App\Http\Requests\MembershipType\StoreRequest;
use App\Http\Requests\MembershipType\UpdateRequest;
use App\Http\Resources\MembershipTypeResource;
use App\Http\Resources\MembershipTypeResourceCollection;
use App\Models\MembershipType;
use App\Repositories\Contracts\MembershipTypeInterface;
use Illuminate\Http\Request;

class MembershipTypeController
{
    private MembershipTypeInterface $membershipType;

    public function __construct(MembershipTypeInterface $membershipType)
    {
        $this->membershipType = $membershipType;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->membershipType->findBy($request->all());
        return new MembershipTypeResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->membershipType->save($request->all());
        return new MembershipTypeResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipType $membershipType)
    {
        return new MembershipTypeResource($membershipType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, MembershipType $membershipType)
    {
        $list = $this->membershipType->update($membershipType, $request->all());
        return new MembershipTypeResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipType $membershipType)
    {
        $this->membershipType->delete($membershipType);
        return response()->json(null, 204);
    }
}
