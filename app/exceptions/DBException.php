<?php
namespace App\Exceptions;

class DBException extends \Exception
{
    public function __construct($message = null, $code = null)
    {
        parent::__construct(
            (!is_null($message)) ? $message : 'BD Error',
            (!is_null($code)) ? $code : 802
        );
    }
}
