<?php
require "../bootstrap.php";
use Src\Controllers\ProductController;
use Src\Controllers\CategoryController;
use Src\Controllers\SaleController;
use Src\Controllers\LoginController;
use Src\Auth;

// Controllers for every path
$controllers = [
    'product' => ProductController::class,
    'category' => CategoryController::class,
    'sale' => SaleController::class,
    'login' => LoginController::class,
];

$unprotected = [
    'login' => true
];

//Set header configurations
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}

$headers = apache_request_headers();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// get module - 'product'| 'category' | 'sale'
$module = $uri[1];

// get the id parameter, is optional
$Id = null;
if (isset($uri[2])) {
    $Id = (int) $uri[2];
}

// get request method - GET | POST | PUT | DELETE
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Validate controller - 'product'| 'category' | 'sale'
// if not valid return 404
if(!isset($controllers[$module])){
    header("HTTP/1.1 404 Not Found");
    exit();
}

function invalidToken(){
    header("HTTP/1.1 403 Invalid Token");
    echo(json_encode([
        'error' => 'Athentication Failed'
    ]));

    exit();
}


// // validate jwt if route is protected
if(!isset($unprotected[$module])){

    // verify token, if no valid token returns error
    try {
        $user = Auth::verifyHeaders($headers);
        
        if(!$user){
            invalidToken();
        }

    } catch (\Throwable $th) {
        invalidToken();
    }
}



// Set the controller which deal with the request
$Controller = $controllers[$module];

// pass the opem db connection to controller
// pass the request method and ID param to the Controller and process the HTTP request:
$controller = new $Controller($dbConnection, $requestMethod, $Id);
$controller->processRequest();