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
use App\Traits\TransactionGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingController
{
    use TransactionGenerator;
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
        throw new CustomValidationException('The name field must be an array.', 422, [
            'tariff_id' => $request->all(),
        ]);
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
        throw new CustomValidationException('The name field must be an array.', 422, [
            'tariff_id' => $request->all(),
        ]);
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
     * @throws \Exception
     */
    function payAll(PayAllDueRequest $request)
    {
//        throw new CustomValidationException('The name field must be an array.', 422, [
//            'tariff_id' => $request->all(),
//        ]);
        try {
            DB::beginTransaction();

            $paymentIds = array_map('intval', $request->input('paymentIds')); // Explicit integer casting


            $selectedPayments = Payment::whereIn('id' , $paymentIds)->get();


            $transactionId = $this->generateTransactionId();

            Payment::whereIn('id', $paymentIds)->update([
                'transaction_id' => $transactionId,
                'reference_number' => $request->query('reference_number') ?? '',
                'txn_number' => $request->query('txn_number') ?? '',
            ]);

            $totalPayableForSelectedTransaction = $this->interface->getAmountToPay($selectedPayments);

            if ($totalPayableForSelectedTransaction == 0){
                    throw new CustomValidationException('The name field must be an array.', 422, [
                        'tariff_id' => 'There is not payable amountt',
                    ]);
            }

            Payment::whereIn('id', $paymentIds)->update([
                'paid_now' => $totalPayableForSelectedTransaction,
            ]);


            $this->interface->applyBatchPayment($paymentIds, $totalPayableForSelectedTransaction, $request->query('paymentMethod'));
            DB::commit();

            return [
                'data' => [
                    'redirect_url' => env('CLIENT_URL').'/success?transaction_id='.$transactionId.'&batch_payment=success',
                ]
            ];

            //todo
            $paymentData = [
                'amount' => $totalPayableForSelectedTransaction,
                'transaction_id' => $transactionId,
            ];
            if ($request->query('paymentMethod') == PaymentMethod::cash->value){
                $this->interface->applyBatchPayment($paymentIds, $totalPayableForSelectedTransaction, PaymentMethod::cash->value);
                DB::commit();

                return [
                    'data' => [
                        'redirect_url' => env('CLIENT_URL').'/success?transaction_id='.$transactionId.'&batch_payment=success',
                    ]
                ];
            }
            DB::commit();
            if ($request->process == 'app'){
                return response()->json([
                    'data' => [
                        'redirect_url' => $this->interface->payBySslCommerz($paymentData)
                    ]
                ]);
            }
            return redirect($this->interface->payBySslCommerz($paymentData));

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if there's an error

            throw $e;
            // Optionally, log the exception or handle it in other ways
        }

    }

    function repayAll(Request $request): \Illuminate\Http\JsonResponse
    {
        throw new CustomValidationException('The name field must be an array.', 422, [
            'tariff_id' => $request->all(),
        ]);
        $queryBuilder = Payment::query();

        if (isset($request['end_date'])) {
            $queryBuilder  =  $queryBuilder->whereDate('created_at', '<=', Carbon::parse($request['end_date']));
        }

        if (isset($request['start_date'])) {
            $queryBuilder =  $queryBuilder->whereDate('created_at', '>=', Carbon::parse($request['start_date']));
        }

        $totalDueAmount = $queryBuilder->sum('payable_amount');

        $updatedTransactionId = $this->generateTransactionId(); // The new transaction ID you want to update
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
