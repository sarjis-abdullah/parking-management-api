<?php

namespace App\Http\Requests\Tariff;

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
            'name' => 'required|string|unique:tariffs,name',
            'default' => 'boolean',
            'place_id' => 'nullable|integer|exists:places,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'start_date' => 'nullable|date',
            'payment_rates' => 'required|array',
            'payment_rates.*.rate' => 'required|numeric|between:1,1000000',
            'type' => 'string',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_rates.*.rate' => 'rate',
        ];
    }
}
