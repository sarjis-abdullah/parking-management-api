<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class PayAllDueRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */

    public function rules(): array
    {
        $this->merge([
            'paymentIds' => explode(',', $this->query('paymentIds', '')),
        ]);
        return [
            'paymentIds' => 'required|array',
            'paymentIds.*' => 'integer|min:1',
            'paymentMethod' => [
                'required',
                Rule::in([
                    "cash",
                    "bkash",
                    "nagad",
                    "rocket",
                    "upay",
                    "tap",
                    "online",
                    "others",
                ])
            ],
            'txn_number' => '',
            'reference_number' => '',
            'process' => ['sometimes', Rule::in(['app', 'scan'])],
        ];
    }

    public function withValidator($validator): void
    {
        // Call the parent logic first
        parent::withValidator($validator);

        // Add custom validation logic
        $validator->after(function ($validator) {
            $method = $this->input('paymentMethod');
            $txnNumber = $this->input('txn_number');
            $referenceNumber = $this->input('reference_number');

            if ($method !== 'cash' && !$txnNumber && !$referenceNumber) {
                $validator->errors()->add(
                    'txn_or_ref',
                    'Either transaction number or reference number is required when payment method is not cash.'
                );
            }
        });
    }
}
