<?php

namespace App\Http\Requests\Slot;

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
            'name' => 'required|string',
            'floor_id' => 'required|integer|exists:floors,id',
            'place_id' => 'required|integer|exists:places,id',
            'category_id' => 'required|integer|exists:categories,id',
            'identity' => 'required|string',
            'remarks' => 'nullable|string',
//            'status' => 'required|integer|between:0,1',
        ];
    }
}
