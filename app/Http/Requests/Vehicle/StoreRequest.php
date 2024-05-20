<?php

namespace App\Http\Requests\Vehicle;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

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
            'number' => 'required|string|unique:vehicles,number',
            'driver_mobile' => 'string',
            'membership_id' => 'string|exists:memberships,id',
        ];
    }
}
