<?php

namespace App\Http\Requests\CashFlow;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'starting_cash' => 'required|numeric|min:0',
            'income' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|numeric|min:0',
            'ending_cash' => 'nullable|numeric|min:0',
//            'date' => 'required|date|unique:cash_flows,date', // Ensure one record per day
        ];
    }

    public function messages(): array
    {
        return [
            'starting_cash.required' => 'The starting cash amount is required.',
            'starting_cash.numeric' => 'The starting cash must be a valid number.',
            'date.unique' => 'A cash flow record for this date already exists.',
        ];
    }
}
