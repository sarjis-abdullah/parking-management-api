<?php

namespace App\Http\Requests\TradingUserCheck;

use App\Http\Requests\Request;

class CreateIFCheckRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:INSTRUMENT_FIT',
            'check_confirmed_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
            'instrument_suitability' => 'required|array',
            'instrument_suitability.suitability' => 'required|boolean'
        ];
    }
}
