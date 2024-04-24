<?php

namespace App\Http\Controllers;

use App\Http\Requests\TradingUser\StoreRequest;
use App\Services\Trading\Contracts\TradingUser;

class TradingUserController extends Controller
{
    /**
     * @var TradingUser
     */
    private $tradingUser;

    public function __construct(TradingUser $tradingUser)
    {
        $this->tradingUser = $tradingUser;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

   /**
    * Store a newly created resource in storage.
    */
    public function store(StoreRequest $request)
    {
        return $this->tradingUser->createUser($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
