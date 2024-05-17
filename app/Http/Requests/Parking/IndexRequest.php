<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

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
            'barcode' => 'string|nullable',
            'vehicle_no' => 'string',
            'query' => 'string',
        ];
    }
}
