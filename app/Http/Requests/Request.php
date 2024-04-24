<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class Request extends FormRequest
{
    /**
     * Empty and default validation rules that apply to the request.
     * Possible override by child class
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * configure the validator instance
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allowableFields = array_merge($this->rules(), [
                'page'              => 'number',
                'per_page'          => 'number',
                'search'            => 'string',
                'order_by'          => 'string',
                'order_direction'   => 'string',
                'include'           => 'string',
                'detailed'          => 'string',
                'userInfo'          => '',
                'withOutPagination' => 'boolean'
            ]);

            foreach ($this->all() as $key => $value) {
                if (!array_key_exists($key, $allowableFields)) {

                    // if it is a IndexRequest return invalid filter message
                    if (strpos(get_called_class(), 'IndexRequest') !== false) {
                        $validator->errors()->add($key, "Invalid filter '". $key ."'.");
                    } else {
                        $validator->errors()->add($key, "Field '". $key ."' does not exist.");
                    }
                }
            }
        });
    }
}
