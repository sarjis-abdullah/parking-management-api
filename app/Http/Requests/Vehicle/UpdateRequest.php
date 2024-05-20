<?php

namespace App\Http\Requests\Vehicle;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');
        return [
            'number' => Rule::unique('vehicles')->ignore($vehicle->number, 'number'),
            'driver_mobile' => 'string',
            'membership_id' => 'string|exists:memberships,id|unique:vehicles,membership_id',
        ];
    }
}
