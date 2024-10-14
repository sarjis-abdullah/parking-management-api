<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class PayAllDueRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paymentIds' => 'required|array',
//            'vehicle_id' => 'required',
//            'status' => 'sometimes|required',
//            'start_date' => 'sometimes|required',
//            'end_date' => 'sometimes|required',
        ];
    }
}
