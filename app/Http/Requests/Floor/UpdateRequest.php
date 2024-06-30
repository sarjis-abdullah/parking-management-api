<?php

namespace App\Http\Requests\Floor;

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
            'name' => 'required|string|unique:categories',
            'remarks' => 'nullable',
//            'place_id' => 'required|integer|exists:places,id',
        ];
    }
}
