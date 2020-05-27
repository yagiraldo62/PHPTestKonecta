<?php
namespace Src\Controllers;

use Src\Controllers\Controller;
use Src\Models\Category;

class CategoryController extends Controller {

    private $db;
    private $requestMethod;
    private $path = '/category';

    private $categoryModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        // Category actions
        $this->categoryModel = new Category($db); 
    }

    // Select action by method
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllCategories();
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
    private function getAllCategories()
    {
        $result = $this->categoryModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
}