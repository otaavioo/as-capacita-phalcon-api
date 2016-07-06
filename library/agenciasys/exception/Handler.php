<?php

namespace agenciasys\exception;

use App\Token;

class Handler
{
    public static function exceptionHandler($exception)
    {
        $header = [
            '_meta' => [
                'status' => 'EXCEPTION',
                'count' => 1
            ]
        ];

        $body = [
            'records' => [
                'errorCode' => $exception->getCode(),
                'errorTitle' => get_class($exception),
                'errorMessage' => $exception->getMessage() . '. File: ' . $exception->getFile() . ', Line: '.$exception->getLine()
            ]
        ];

        header("Content-Type: application/json");
        echo json_encode($header + $body);
        die();
    }

    public static function set()
    {
        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
    }
}
