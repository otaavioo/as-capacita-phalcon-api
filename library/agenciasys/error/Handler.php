<?php

namespace agenciasys\error;

use AgenciaSys\Error\Message;

class Handler
{
    public static function handle($errno, $errstr, $errfile, $errline, $errcontext)
    {
        (new Message($errno, 'Erro: '.$errstr.'. Arquivo: '.$errfile.'. Linha: '.$errline, $errcontext))->toArray();
    }

    public static function set()
    {
        set_error_handler(array(__CLASS__, 'handle'));
    }
}
