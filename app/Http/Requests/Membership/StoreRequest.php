<?php

namespace App\Http\Requests\Membership;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'vehicle_id' => 'integer|exists:vehicles,id|unique:memberships,vehicle_id',
            'contact_number' => 'required|unique:memberships,contact_number',
        ];
    }

    public function messages()
    {
        return [
            'vehicle_id.unique' => 'The vehicle is already registered for the membership program.',
            'contact_number.unique' => 'The contact number is already registered for the membership program.',
        ];
    }
}
