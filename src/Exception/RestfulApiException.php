<?php
namespace yyc\Exception;

use Throwable;

class RestfulApiException extends \Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        $data = [
            'message' => $message,
            'code' => $code,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace(),
        ];
        echo json_encode($data);
    }

}