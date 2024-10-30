<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Exceptions\CustomValidationException;
use App\Http\Requests\Parking\CheckoutRequest;
use App\Http\Requests\Parking\IndexRequest;
use App\Http\Requests\Parking\StoreRequest;
use App\Http\Requests\Parking\UpdateRequest;
use App\Http\Requests\Payment\PayAllDueRequest;
use App\Http\Resources\ParkingResource;
use App\Http\Resources\ParkingResourceCollection;
use App\Models\Parking;
use App\Models\Payment;
use App\Repositories\Contracts\ParkingInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    function payAllDue(PayAllDueRequest $request)
    {
        DB::beginTransaction();

        $paymentIds = $request->paymentIds;

        $selectedPayments = Payment::whereIn('id' , $paymentIds)->get();

        $transactionId = uniqid();

        Payment::whereIn('id', $paymentIds)->update([
            'transaction_id' => $transactionId,
        ]);

        $totalPayableForSelectedTransaction = 0;

        // Assuming you have a collection of payments
        foreach ($selectedPayments as $payment) {
            if ($payment->status == 'success' && $payment->payment_type == "partial") {
                // Add total payable when status is not success
                $totalPayableForSelectedTransaction += floatval($payment->due_amount);
                continue; // Move to the next payment in the loop
            }elseif ($payment->status != 'success') {
                // Add total due when payment type is not full
                $totalPayableForSelectedTransaction += floatval($payment->payable_amount);
            }
        }

        Payment::whereIn('id', $paymentIds)->update([
            'paid_now' => $totalPayableForSelectedTransaction,
        ]);

        $paymentData = [
            'amount' => $totalPayableForSelectedTransaction,
            'transaction_id' => $transactionId,
        ];

        if ($request->paymentMethod == PaymentMethod::cash->value){
            Payment::whereIn('id', $paymentIds)->update([
                'status' => PaymentStatus::success,
                'date'   => now(),
            ]);

            DB::commit();

            return [
                'data' => [
                    'redirect_url' => env('CLIENT_URL').'/success?transaction_id='.$transactionId.'&batch_payment=success',
                ]
            ];
        }
        DB::commit();
        return response()->json([
            'data' => [
                'redirect_url' => $this->interface->payBySslCommerz($paymentData)
            ]
        ]);
    }

    function repayAll(Request $request): \Illuminate\Http\JsonResponse
    {

        $queryBuilder = Payment::query();

        if (isset($request['end_date'])) {
            $queryBuilder  =  $queryBuilder->whereDate('created_at', '<=', Carbon::parse($request['end_date']));
        }

        if (isset($request['start_date'])) {
            $queryBuilder =  $queryBuilder->whereDate('created_at', '>=', Carbon::parse($request['start_date']));
        }

        $totalDueAmount = $queryBuilder->sum('payable_amount');

        $updatedTransactionId = uniqid(); // The new transaction ID you want to update
        $queryBuilder->update(['transaction_id' => $updatedTransactionId]);

        $paymentData = [
            'amount' => $totalDueAmount,
            'transaction_id' => $updatedTransactionId,
        ];

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
//        throw new CustomValidationException('The name field must be an array.', 422, [
//            'tariff_id' => $request->all(),
//        ]);
//        return [
//            'data' => [
//                'redirect_url' => env('CLIENT_URL').'/success?transaction_id=6709f2c85bd18'
//            ]
//        ];
//        return $request->all();
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
