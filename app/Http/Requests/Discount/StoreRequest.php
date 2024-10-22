<?php

namespace App\Http\Requests\Discount;

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
            'type' => 'required|in:percentage,flat',
//            'type' => 'required|in:percentage,flat,promo_code,time_based,loyalty,bulk',
            'amount' => 'required|numeric|min:0',
            'promo_code' => 'required|string|max:50|unique:discounts,promo_code',
            'valid_for_hours' => 'nullable|integer|min:1',
            'valid_after_visits' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }
}
