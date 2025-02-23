<?php

namespace App\Traits;

use App\Models\Payment;

trait TransactionGenerator
{
    public function generateTransactionId(): int
    {
        do {
            $transactionId = mt_rand(100000, 999999);
        } while (Payment::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }
}
