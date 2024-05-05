<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    public $is_success;
    public $message;
    public $code;
    public $data;

    public function __construct($message, $code, $data = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
        parent::__construct($message, $code);
    }
//    public function __construct($is_success, $message, $code, $data = null, Exception $previous = NULL)
//    {
//        $this->is_success = $is_success;
//        $this->message = $message;
//        $this->code = $code;
//        $this->data = $data;
//    }

    public function render(): JsonResponse
    {
        return response()->json([
//            'success' => $this->is_success,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->code);
    }
}


//namespace App\Services\Trading\Upvest;

//use Exception;
//
//class UpvestException extends Exception
//{
//    /**
//     * Render the exception into an HTTP response.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function render($request)
//    {
//        $errorMessage = json_decode($this->getMessage());
//
//        return response()->json([
//            'message' => $errorMessage->title ?? 'An error occurred in trading API',
//            'errors' => ['detail' => [$errorMessage->detail ?? 'An error occurred in trading API']]
//        ], $this->getCode());
//    }
//}
