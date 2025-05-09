<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;

class IndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|string',
        ];
    }
}
