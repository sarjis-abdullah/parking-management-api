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
            'paymentMethod' => ['required', Rule::in(['cash', 'online'])],
            'process' => ['sometimes', Rule::in(['app', 'scan'])],
        ];
    }
}
