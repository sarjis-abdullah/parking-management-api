<?php

namespace App\Http\Requests\Report\Vehicle;

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
            'start_date' => 'date|date_format:Y-m-d',
            'end_date' => 'date|date_format:Y-m-d',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'format' => ['sometimes', Rule::in(['json', 'pdf'])],
            'load' => ['sometimes', Rule::in(['view'])],
        ];
    }
}
