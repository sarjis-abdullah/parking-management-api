<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'district_code' => 'required|string',
            'vehicle_no' => 'required|string',
            'driver_name' => 'nullable|string',
            'driver_mobile' => 'nullable|string',
            'place_id' => 'required|integer|exists:places,id',
            'category_id' => 'required|integer|exists:categories,id',
            'slot_id' => 'required|integer|exists:slots,id',
            'floor_id' => 'required|integer|exists:floors,id',
            'tariff_id' => 'nullable|integer|exists:tariffs,id',
//            'payment.method' => 'required|integer',
//            'payment.paid_amount' => 'required|integer',
        ];
    }
}
