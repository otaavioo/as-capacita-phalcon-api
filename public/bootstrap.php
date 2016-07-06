<?php

define('APPLICATION_PATH', realpath(__DIR__ . '/../'));

if (getenv('APPLICATION_ENV') == 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_STRICT);
}

locale_set_default('pt-BR');
date_default_timezone_set('America/Sao_Paulo');
