<?php
namespace Src\Controllers;
use Src\Models\Product;
use Src\Controllers\Controller;


class ProductController extends Controller {

    private $db;
    private $requestMethod;
    private $id;
    private $path = '/product';

    private $productModel;

    public function __construct($db, $requestMethod, $id)
    {
        // Set Open DB Connection
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        // Product actions
        $this->productModel = new Product($db); 
    }

    // Select action by method
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->getProduct($this->id);
                } else {
                    $response = $this->getAllProducts();
                };
                break;
            case 'POST':
                $response = $this->createProductFromRequest();
                break;
            case 'PUT':
                $response = $this->updateProductFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->inactivateProduct($this->id);
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
    private function getAllProducts()
    {
        $result = $this->productModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    //GET /id
    private function getProduct($id)
    {
        $result = $this->productModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result[0]);
        return $response;
    }

    //POST 
    private function createProductFromRequest()
    {
        $input = $_POST;
        if (! $this->validateProduct($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->productModel->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    //PUT
    private function updateProductFromRequest($id)
    {
        $result = $this->productModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = $_POST;
        if (! $this->validateProduct($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->productModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    //DELETE
    private function inactivateProduct($id)
    {
        $result = $this->productModel->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->productModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }


    //Validations
    private function validateProduct($input,$id = false)
    {
        if (!isset($input['id']) && $id) {
            return false;
        }
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['reference'])) {
            return false;
        }
        if (! isset($input['price'])) {
            return false;
        }
        if (! isset($input['weight'])) {
            return false;
        }
        if (! isset($input['category'])) {
            return false;
        }
        return true;
    }
}