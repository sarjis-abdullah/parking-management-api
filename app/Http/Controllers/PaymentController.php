<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Exceptions\CustomValidationException;
use App\Http\Requests\Payment\UpdateRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController
{
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
}
