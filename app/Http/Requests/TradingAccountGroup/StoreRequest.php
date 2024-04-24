<?php

namespace App\Http\Requests\TradingAccountGroup;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid',
            'type' => 'required|in:PERSONAL,LEGAL_ENTITY'
        ];
    }
}
