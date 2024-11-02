<?php

namespace App\Http\Requests\Report\Transaction;

use App\Enums\PaymentStatus;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'string',
            'start_date' => 'date|date_format:Y-m-d',
            'end_date' => 'date|date_format:Y-m-d',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'payment_type' => 'sometimes|in:full,partial',
            'category' => 'sometimes',
            'status' => ['sometimes', Rule::enum(PaymentStatus::class)],
            'method' => ['sometimes', Rule::in(['cash', 'ssl_commerz', 'due'])],
            'format' => ['sometimes', Rule::in(['json', 'pdf'])],
            'load' => ['sometimes', Rule::in(['view'])],
            'discount_filter' => ['sometimes', Rule::in(['all', 'no_discount', 'membership_discount', 'other_discount'])],
        ];
    }
}
