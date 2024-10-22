<?php

namespace App\Http\Requests\Discount;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $discount = $this->route('discount');
        return [
            'type' => 'sometimes|required|in:percentage,flat,promo_code,time_based,loyalty,bulk',
//            'amount' => 'required|numeric|min:0',
            'promo_code' => 'string|max:50|unique:discounts,promo_code,' . $discount->id,
            'valid_for_hours' => 'nullable|integer|min:1',
            'valid_after_visits' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
//            'allow_separate_discount' => 'required|boolean',
        ];
    }
}
