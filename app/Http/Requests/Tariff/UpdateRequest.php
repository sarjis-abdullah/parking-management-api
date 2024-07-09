<?php

namespace App\Http\Requests\Tariff;

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
        $tariff = $this->route('tariff');

        return [
            'name' => Rule::unique('tariffs')->ignore($tariff->name, 'name'),

//            'name' => 'required|string|unique:tariffs,name',
            'default' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ];
    }
}
