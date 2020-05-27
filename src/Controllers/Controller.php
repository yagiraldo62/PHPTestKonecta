<?php
namespace Src\Controllers;
// logger for request
use Src\Logger;

class Controller {
    //Error response when input is invalid
    public function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    //Error response for not found request
    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    // this will register every request
    public function logRequest($method, $path, $response_header){
        Logger::log(" {$method} {$path} : {$response_header}");
    }

    public function log($message){
        Logger::log($message);
    }
}