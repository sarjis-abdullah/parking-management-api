<?php

namespace App\Http\Requests\TradingAccount;

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
            'account_group_id' => 'required|uuid',
            'type' => 'required|in:TRADING,PORTFOLIO',
            'name' => 'string|max:255' // Adjust the max length as needed
        ];
    }
}
