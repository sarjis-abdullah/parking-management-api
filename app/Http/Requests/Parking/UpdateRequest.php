<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_no' => 'required|string',
            'driver_name' => 'nullable|string',
            'driver_mobile' => 'nullable|string',
            'place_id' => 'required|integer|exists:places,id',
            'category_id' => 'required|integer|exists:categories,id',
            'slot_id' => 'required|integer|exists:slots,id',
            'floor_id' => 'required|integer|exists:floors,id',
            'tariff_id' => 'nullable|integer',
//            'out_time' => 'required|date_format:Y-m-d H:i:s',
//            'payment' => 'required',
//            'payment.method' => 'required|string',
//            'payment.paid_amount' => 'required|numeric',
        ];
    }
}
