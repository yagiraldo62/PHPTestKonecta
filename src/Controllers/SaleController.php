<?php
namespace Src\Controllers;

use Src\Controllers\Controller;
use Src\Models\Sale;
use Src\Models\Product;

class SaleController extends Controller {

    private $db;
    private $requestMethod;
    private $path = '/sale';

    private $saleModel;
    private $productModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        // Sale and Product actions
        $this->saleModel = new Sale($db); 
        $this->productModel = new Product($db); 
    }

    // Select action by method
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllSales();
                break;
            case 'POST':
                $response = $this->createSaleFromRequest();
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
    private function getAllSales()
    {
        $result = $this->saleModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    //POST
    private function createSaleFromRequest()
    {
        $input = $_POST;
        if (! $this->validateSale($input)) {
            return $this->unprocessableEntityResponse();
        }

         $response['status_code_header'] = 'HTTP/1.1 201 Created';
        if(!$this->productModel->dicreaseStock($input['product'],(int) $input['quantity'])){
            return $this->insufficientStock();
        }
        $this->saleModel->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }


    //Error response if stock is insufficient when creating a sale
    private function insufficientStock(){
        $response['status_code_header'] = 'HTTP/1.1 500 Insufficient Stock';
        $response['body'] = null;
        return $response;
    }


    //Validations
    private function validateSale($input)
    {
        if (!isset($input['product'])) {
            return false;
        }
        if (! isset($input['quantity'])) {
            return false;
        }
        if (! isset($input['price'])) {
            return false;
        }
        return true;
    }
}