<?php
namespace Src\Models;
class User   {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    //GET
    public function login($input)
    {
        $username = $input['username'];
        $pass = $input['pass'];
        $statement = "
        SELECT 
        id, username 
        FROM user 
        WHERE username = '{$username}' AND pass = '{$pass}' Limit 1;";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}