<?php
namespace Src\Models;

class Category {

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
                id, name
            FROM
                categories
            WHERE categories.active = 1;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}