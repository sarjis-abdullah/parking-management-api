<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomValidationException extends Exception
{
    public $message;
    public $code;
    public $key;

    public function __construct($message, $code,$key)
    {
        $this->message = $message;
        $this->code = $code;
        $this->key = $key;
        parent::__construct($message, $code);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'key' => $this->key,
            'customValidation' => true,
        ], $this->code);
    }
}
