<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\UpdateRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController
{
    function update(UpdateRequest $request, Payment $payment)
    {
        $payment->update($request->all());
        return new PaymentResource($payment);
    }
}
