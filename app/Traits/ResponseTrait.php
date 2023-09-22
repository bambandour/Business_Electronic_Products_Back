<?php

namespace App\Traits;

trait ResponseTrait
{
    public function formatResponse($message, $data = [], $success = true, $statusCode = 200,$error="")
    {
        return [
            "message" => $message,
            "data" => $data,
            "success" => $success,
            "error"=>$error
        ];
    }
}