<?php

namespace App\Http\Requests\Instrument;

use App\Http\Requests\Request;

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
            'order_by' => 'in:created_at,updated_at',
            'per_page' => 'integer|min:1|max:1000',
            'order_direction' => 'in:asc,desc',
            'trading_status' => 'in:ACTIVE,INACTIVE',
        ];
    }


}
