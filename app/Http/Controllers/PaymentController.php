<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomValidationException;
use App\Http\Requests\Payment\IndexRequest;
use App\Http\Requests\Payment\UpdateRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PaymentResourceCollection;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentInterface;
use Illuminate\Http\Request;

class PaymentController
{
    private PaymentInterface $paymentInterface;

    public function __construct(PaymentInterface $paymentInterface)
    {
        $this->paymentInterface = $paymentInterface;
    }

    function index(IndexRequest $request)
    {
        $list = $this->paymentInterface->findBy($request->all());
        return new PaymentResourceCollection($list);
    }

    /**
     * @throws CustomValidationException
     */
    function update(UpdateRequest $request, Payment $payment)
    {
        $shouldTrue = ((double)$request->discount_amount + (double)$request->paid_amount) == (double)$payment->due_amount;
        if (!$shouldTrue){
            throw new CustomValidationException('The name field must be an array.', 422, [
                'error' => ['Something wrong.'],
            ]);
        }
        $request->due_amount = 0;
        $payment->update([
            ...$request->all(),
            'due_amount' => 0
        ]);
        return new PaymentResource($payment);
    }

    function repay(Request $request)
    {

    }
}
