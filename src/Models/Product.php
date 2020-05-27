<?php
namespace Src\Models;

class Product {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // GET
    public function findAll()
    {
        $statement = "
            SELECT 
                products.id, products.name, reference, price, weight, stock, created_at, last_sale_at, categories.name AS category, products.category AS category_id
            FROM
                products
            INNER JOIN categories 
                ON categories.id = products.category;
                WHERE products.active = 1;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    // GET /id
    public function find($id)
    {
        $statement = "
            SELECT 
                products.id, products.name, reference, price, weight, stock, created_at, last_sale_at, categories.name AS category, products.category AS category_id
            FROM
                products
            INNER JOIN categories 
                ON categories.id = products.category
            WHERE products.id = ? AND products.active = 1;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    // POST
    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO products 
                (name, reference, price, weight, category, stock)
            VALUES
                (:name, :reference, :price, :weight, :category, :stock);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'reference' => $input['reference'],
                'price' => $input['price'],
                'weight' => $input['weight'],
                'category' => $input['category'],
                'stock' => $input['stock'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    // PUT
    public function update($id, Array $input)
    {
        $statement = "
            UPDATE products
            SET 
                name = :name,
                reference = :reference,
                price = :price,
                weight = :weight,
                category = :category,
                stock = :stock
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'reference' => $input['reference'],
                'price' => $input['price'],
                'weight' => $input['weight'],
                'category' => $input['category'],
                'stock' => $input['stock'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }


    // DELETE
    public function delete($id)
    {
        $statement = "
            UPDATE products
                SET active = 0
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }


    // validate if there is sufficient stock
    // update stock value and last sale date
    public function dicreaseStock($id,int $quantity)
    {
        $product = $this->find($id)[0];
        $stock = (int) $product['stock'] - $quantity;
        if( $stock < 0 ) return false;
        $statement = "
            UPDATE products
            SET 
                stock = :stock,
                last_sale_at = :last_sale_at
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'stock' => $stock,
                'last_sale_at' => date("Y-m-d H:i:s"),
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

   
}