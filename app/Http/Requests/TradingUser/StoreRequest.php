<?php

namespace App\Http\Requests\TradingUser;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:100',
            'salutation' => 'in:SALUTATION_MALE,SALUTATION_FEMALE,SALUTATION_FEMALE_MARRIED,SALUTATION_DIVERSE',
            'title' => 'in:DR,PROF,PROF_DR,DIPL_ING,MAGISTER',
            'birth_date' => 'required|date_format:Y-m-d',
            'birth_city' => 'required|string|min:1|max:85',
            'birth_country' => 'required|regex:/^[A-Z]{2}$/',
            'birth_name' => 'string|max:100',
            'nationalities' => 'required|array|min:1',
            'nationalities.*' => 'regex:/^[A-Z]{2}$/',
            'phone_number' => 'regex:/^([0-9]{8,15})?$/',
            'address' => 'required|array',
            'address.address_line1' => 'required|string|max:100',
            'address.address_line2' => 'string|max:100',
            'address.postcode' => 'required|regex:/^[a-zA-Z0-9\s\-]{1,10}$/',
            'address.city' => 'required|string|min:1|max:85',
            'address.state' => 'string|max:50',
            'address.country' => 'required|regex:/^[A-Z]{2}$/',
            'postal_address' => 'array',
            'postal_address.address_line1' => 'required_with:postal_address|string|max:100',
            'postal_address.address_line2' => 'string|max:100',
            'postal_address.postcode' => 'required_with:postal_address|regex:/^[a-zA-Z0-9\s\-]{1,10}$/',
            'postal_address.city' => 'required_with:postal_address|string|min:1|max:85',
            'postal_address.state' => 'string|max:50',
            'postal_address.country' => 'required_with:postal_address|regex:/^[A-Z]{2}$/',
            'terms_and_conditions' => 'required|array',
            'terms_and_conditions.consent_document_id' => 'required|uuid',
            'terms_and_conditions.confirmed_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
            'data_privacy_and_sharing_agreement' => 'required|array',
            'data_privacy_and_sharing_agreement.consent_document_id' => 'required|uuid',
            'data_privacy_and_sharing_agreement.confirmed_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
            'fatca' => 'required|array',
            'fatca.status' => 'required|boolean',
            'fatca.confirmed_at' => 'required|date_format:Y-m-d\TH:i:s\Z',
        ];
    }
}
