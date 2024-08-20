<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomValidationException extends Exception
{
    public $message;
    public $code;
    public $errors;

    public function __construct($message, $code, $errors)
    {
        $this->message = $message;
        $this->code = $code;
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ], $this->code);
    }
}
