<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_no' => 'required|string',
            'driver_name' => 'string',
            'driver_mobile' => 'string',
            'place_id' => 'required|integer|exists:places,id',
            'category_id' => 'required|integer|exists:categories,id',
            'slot_id' => 'required|integer|exists:slots,id',
        ];
    }
}
