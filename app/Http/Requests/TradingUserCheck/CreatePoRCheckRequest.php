<?php

namespace App\Http\Requests\TradingUserCheck;

use App\Http\Requests\Request;

class CreatePoRCheckRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:POR',
            'check_confirmed_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
            'issuance_date' => 'required|date_format:Y-m-d',
            'data_download_link' => 'required|url|max:1000',
            'document_type' => 'required|in:UTILITY_BILL,TELEPHONE_BILL,INTERNET_BILL,BANK_STATEMENT,REGISTRATION_CERT,RESIDENCE_PERMIT,ID_CARD',
            'confirmed_address' => 'required|array',
            'confirmed_address.address_line1' => 'required|string|max:100',
            'confirmed_address.address_line2' => 'nullable|string|max:100',
            'confirmed_address.postcode' => 'required|regex:/^[a-zA-Z0-9\s\-]{1,10}$/',
            'confirmed_address.city' => 'required|string|min:1|max:85',
            'confirmed_address.state' => 'nullable|string|max:50',
            'confirmed_address.country' => 'required|regex:/^[A-Z]{2}$/'
        ];
    }
}
