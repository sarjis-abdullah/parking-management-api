<?php

namespace App\Http\Requests\Discount;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'string|max:255',
            'type' => 'in:percentage,flat,promo_code,time_based,loyalty,bulk',
            'amount' => 'numeric|min:0',
            'promo_code' => 'nullable|string|max:50',
            'valid_for_hours' => 'nullable|integer|min:1',
            'valid_after_visits' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ];
    }
}
