<?php

namespace App\Http\Requests\MembershipType;

use App\Http\Requests\Request;
use App\Models\MembershipType;
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
        return [
            'name' => 'required|string|unique:membership_types,id',
            'discount_type' => ['required', Rule::in(['percentage', 'free', 'flat'])],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'default' => ['required', 'boolean'],
            'allow_separate_discount' => ['sometimes', 'boolean'],
        ];
    }
}
