<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
use App\Rules\RefOrTnxRequired;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckoutRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'out_time' => 'required|date_format:Y-m-d H:i:s',
            'duration' => 'required|integer',
//            'payment' => 'required',
            'payment.method' => 'required|string',
            'payment.paid_amount' => 'required|numeric|min:0',
            'payment.payable_amount' => 'required|numeric|min:0',
            'payment.discount_amount' => 'required|numeric|min:0',
            'payment.discount_id' => 'sometimes|required|exists:discounts,id',
            'payment.membership_discount' => 'required|numeric|min:0',
            'payment.txn_number' => '',
            'payment.reference_number' => '',
            'payment' => ['required', new RefOrTnxRequired()]
        ];
    }
}
