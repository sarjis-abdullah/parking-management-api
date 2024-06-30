<?php

namespace App\Http\Requests\Place;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $states  = array_column(\App\Enums\PlaceStatus::cases(), 'value');
        return [
            'name' => 'string',
            'description'  => 'nullable|string',
            'status'  => [Rule::in($states)],
        ];
    }
}
