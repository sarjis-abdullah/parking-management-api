<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RefOrTnxRequired implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $method = request()->input('payment.method');
        $refNumber = request()->input('payment.reference_number');
        $tnxNumber = request()->input('payment.txn_number');
        // Rule: If method is not 'cash', either or both refNumber or tnxNumber must be provided
        if (($method !== 'cash' && $method !== 'online') && !$refNumber && !$tnxNumber) {
            $fail('Either a reference number or a transaction number is required when the payment method is not cash.');
        }
    }
}
