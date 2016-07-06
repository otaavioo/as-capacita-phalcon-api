<?php

namespace AgenciaSys\Error;

class Message
{
    const OTHER_ERROR = 800;

    private $url;
    private $errorCode;
    private $errorTitle;
    private $errorMessage;

    public function __construct($errorCode, $param)
    {
        $server = (new \Phalcon\Http\Request())->getServer('REQUEST_URI');

        $this->url = '';
        if (isset($server)) {
            $this->url = $server;
        }

        switch ($errorCode) {
            default:
                $this->errorCode = self::OTHER_ERROR;
                $this->errorTitle = 'Erro não definido ('.$this->url.')';
                $this->errorMessage = 'Log do erro: '.$param;
                break;
        }
    }

    public function toArray()
    {
        $header = [
            '_meta' => [
                'status' => 'ERROR',
                'count' => 1,
            ],
        ];

        $body = [
            'records' => [
                'errorCode' => $this->errorCode,
                'errorTitle' => $this->errorTitle,
                'errorMessage' => $this->errorMessage,
            ],
        ];

        header("Content-Type: application/json");
        echo json_encode($header + $body);
        die;
    }
}
