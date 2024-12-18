<?php

namespace App\Http\Requests\User;

use App\Enums\RolesAndPermissions;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => ['required', 'string', Rule::in([RolesAndPermissions::ADMIN,RolesAndPermissions::OPERATOR,])],
            'confirmPassword' => 'required|string|min:6|same:password',
//            'status' => 'required|in:active,inactive',
        ];
    }
}
