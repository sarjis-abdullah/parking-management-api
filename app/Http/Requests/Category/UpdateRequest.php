<?php

namespace App\Http\Requests\Category;

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
        $category = $this->route('category');
        return [
            'name' => Rule::unique('categories')->ignore($category->name, 'name'),
            'description' => 'nullable|string',
        ];
    }
}
