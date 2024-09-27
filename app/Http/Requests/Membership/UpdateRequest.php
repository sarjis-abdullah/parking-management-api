<?php

namespace App\Http\Requests\Membership;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends Request
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
            'active' => 'in:0,1',
//            'vehicle_id' => 'integer|exists:vehicles,id',
            'membership_type_id' => 'integer|exists:membership_types,id',
        ];
    }
}
