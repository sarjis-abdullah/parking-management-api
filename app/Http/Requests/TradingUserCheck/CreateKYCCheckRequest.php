<?php

namespace App\Http\Requests\TradingUserCheck;

use App\Http\Requests\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CreateKYCCheckRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:KYC',
            'check_confirmed_at' => 'required|date|after_or_equal:' . Carbon::now()->subMonths(24)->toDateString(),
            'data_download_link' => 'required|url|max:1000',
            'document_type' => 'required|string|in:PASSPORT,ID_CARD,RESIDENCE_PERMIT',
            'document_expiration_date' => 'required|date|after:today',
            'nationality' => 'required|regex:/^[A-Z]{2}$/',
            'provider' => 'required|string|max:100',
            'method' => [
                'required',
                'string',
                Rule::in(['VIDEO_ID', 'IN_PERSON_ID', 'ELECTRONIC_ID', 'LIVENESS_PHOTO_ID']),
                function ($attribute, $value, $fail) {
                    if ($value == 'LIVENESS_PHOTO_ID' && $this->nationality == 'DE') {
                        $fail('LIVENESS_PHOTO_ID method is not allowed if the residence country is Germany.');
                    }
                },
            ],
            'confirmed_address' => 'required|array',
            'confirmed_address.address_line1' => 'required|string',
            'confirmed_address.address_line2' => 'string',
            'confirmed_address.postcode' => 'required|string',
            'confirmed_address.city' => 'required|string',
            'confirmed_address.state' => 'required|string',
            'confirmed_address.country' => 'required|regex:/^[A-Z]{2}$/',
        ];
    }
}
