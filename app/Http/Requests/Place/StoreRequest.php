<?php

namespace App\Http\Requests\Place;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $states  = array_column(\App\Enums\PlaceStatus::cases(), 'value');
        return [
            'name' => 'required|string',
            'description'  => 'nullable',
//            'place_id'  => 'required|exists:places,id',
//            'status'  => ['required', Rule::in($states)],
        ];
    }
}
