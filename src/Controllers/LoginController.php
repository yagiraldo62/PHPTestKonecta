<?php
namespace Src\Controllers;
 
use Src\Controllers\Controller;
use Src\Auth;
use Src\Models\User;

class LoginController extends Controller {

    private $db;
    private $requestMethod;
    private $path = '/login';

    private $UserModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        // User actions
        $this->UserModel = new User($db); 
    }

    // Select action by method
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->login();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        //Log request result
        $this->logRequest($this->requestMethod, $this->path, $response['status_code_header']);

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    //GET
    private function login()
    {
        $input = $_POST;

        if (! $this->validateLogin($input)) {
            return $this->unprocessableEntityResponse();
        }

        $result = $this->UserModel->login($input);

        if( count($result) !== 1){
            return $this->notFoundUserResponse();
        }

        $token = Auth::Token($result);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'message' => 'Correct Authentication',
            token => $token
        ]);
        return $response;
    }

    //Validations
    private function validateLogin($input,$id = false)
    {
        if (! isset($input['username'])) {
            return false;
        }
        if (! isset($input['pass'])) {
            return false;
        }
        return true;
    }

    //Error response when Athentication Failed is invalid
    public function notFoundUserResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Athentication Failed';
        $response['body'] = json_encode([
            'error' => 'Athentication Failed'
        ]);
        return $response;
    }
}