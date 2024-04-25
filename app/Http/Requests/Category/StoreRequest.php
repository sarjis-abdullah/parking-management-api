<?php

namespace App\Http\Requests\Category;

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
            'name' => 'required|string|unique:categories',
            'description' => 'string',
            'place_id' => 'required|integer|exists:places,id',
            'limit_count' => 'integer',
            'status' => 'required|in:active,inactive',
        ];
    }
}
