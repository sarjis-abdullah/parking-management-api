<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\Parking\CheckoutRequest;
use App\Http\Requests\Parking\IndexRequest;
use App\Http\Requests\Parking\StoreRequest;
use App\Http\Requests\Parking\UpdateRequest;
use App\Http\Resources\ParkingResource;
use App\Http\Resources\ParkingResourceCollection;
use App\Models\Parking;
use App\Models\Payment;
use App\Repositories\Contracts\ParkingInterface;
use Illuminate\Http\Request;

class ParkingController
{
    private ParkingInterface $interface;

    public function __construct(ParkingInterface $interface)
    {
        $this->interface = $interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new ParkingResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new ParkingResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Parking $parking)
    {
        return new ParkingResource($parking);
    }

    function repay(Request $request, $paymentId)
    {
        $payment = Payment::find($paymentId);

        $amount = $payment->payable_amount;;
        if ($payment->payment_type == 'partial'){
            $amount = $payment->due_amount;
        }
        $paymentData = [
            'amount' => $amount,
            'transaction_id' => $payment->transaction_id,
        ];
        $routeName = $request->route()->getName();

        if ($routeName == 'scan.repay'){
            $paymentData['scan-checkout'] = true;
        }

        return response()->json([
            'data' => [
                'redirect_url' => $this->interface->payBySslCommerz($paymentData)
            ]
        ]);
    }
    function payDue(Request $request, $paymentId)
    {
        $payment = Payment::find($paymentId);

        $amount = $payment->due_amount;
        if ($payment->payment_type == 'full' and $payment->status = PaymentStatus::pending->value){
            $amount = $payment->payable_amount;
        }
        $paymentData = [
            'amount' => $amount,
            'transaction_id' => $payment->transaction_id,
        ];
        $routeName = $request->route()->getName();

        if ($routeName == 'scan.payDue'){
            $paymentData['scan-checkout'] = true;
        }

        return response()->json([
            'data' => [
                'redirect_url' => $this->interface->payBySslCommerz($paymentData)
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Parking $parking)
    {
        $list = $this->interface->update($parking, $request->all());
        return new ParkingResource($list);
    }
    /**
     * Update the specified resource in storage.
     */
    public function handleCheckout(CheckoutRequest $request, Parking $parking)
    {
        return $this->interface->handleCheckout($parking, $request->all());
        return new ParkingResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parking $parking)
    {
        $this->interface->delete($parking);
        return response()->json(null, 204);
    }
}
