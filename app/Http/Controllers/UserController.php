<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\User;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Hash;

class UserController
{
    private UserInterface $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->userInterface->findBy($request->all());
        return new UserResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        return $this->register($request);
    }

    public function register(RegisterRequest $request)
    {
        if (empty($request->password)){
            $pass = Hash::make('start!23');
        }
        $list = $this->userInterface->save([...$request->all(), 'password' => $pass]);
        return new UserResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
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
    public function update(UpdateRequest $request, User $user)
    {
        $list = $this->userInterface->update($user, $request->validated());
        return new UserResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userInterface->delete($user);
        return response()->json(null, 204);
    }
}
