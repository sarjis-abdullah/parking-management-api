<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
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
            'payment' => 'required',
            'payment.method' => 'required|string',
            'payment.paid_amount' => 'required|numeric',
            'payment.payable_amount' => 'required|numeric',
            'payment.discount_amount' => 'required|numeric',
        ];
    }
}
