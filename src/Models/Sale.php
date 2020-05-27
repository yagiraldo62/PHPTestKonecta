<?php
namespace Src\Models;

class Sale {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    //GET
    public function findAll()
    {
        $statement = "
            SELECT 
                id, quantity, price, products.name as product
            FROM product_sale
            INNER JOIN products ON products.id = product_sale.product
            INNER JOIN categories ON products.category = categories.id
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    //POST
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO product_sale 
                (product, quantity, price)
            VALUES
                (:product, :quantity, :price);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'product' => $input['product'],
                'quantity' => $input['quantity'],
                'price' => $input['price']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}